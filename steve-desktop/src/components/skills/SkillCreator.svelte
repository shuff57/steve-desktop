<script lang="ts">
  import { SKILL_CREATION_PROMPT } from "../../lib/skill-creation-prompt";
  import { sendAgentMessage } from "../../lib/agent-api";
  import { parseSkillMarkdown } from "../../lib/skill-parser";
  import { splitChatSegments } from "../../lib/chat-segments";
  import { saveSkill } from "../../lib/db";

  interface ChatMessage {
    /** Stable key so appending a reply never re-renders the bubbles above it. */
    id: string;
    role: "user" | "assistant";
    content: string;
  }

  const message = (role: ChatMessage["role"], content: string): ChatMessage => ({
    id: crypto.randomUUID(),
    role,
    content,
  });

  let messages: ChatMessage[] = $state([]);
  let inputValue = $state("");
  let isLoading = $state(false);
  let errorText = $state("");
  let lastAssistantHasMarkdown = $state(false);
  let saveSuccess = $state("");
  let chatContainer: HTMLElement | undefined = $state(undefined);

  /** Format conversation history into a single prompt string for the AI. */
  function buildContextualPrompt(newMessage: string): string {
    // If this is the first real user message, send it directly
    const prior = messages.filter(
      (m) => !(m.role === "assistant" && m === messages[0]),
    );
    if (prior.length === 0) return newMessage;

    // Build conversation transcript for context
    const transcript = prior
      .map((m) => `${m.role === "user" ? "User" : "Assistant"}: ${m.content}`)
      .join("\n\n");

    return `Previous conversation:\n${transcript}\n\nUser: ${newMessage}\n\nPlease continue the conversation naturally, taking into account everything discussed above.`;
  }

  /** Scroll chat to the bottom after new messages. */
  function scrollToBottom() {
    requestAnimationFrame(() => {
      if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
      }
    });
  }

  /** Check if a message contains a markdown code block. */
  function hasMarkdownBlock(content: string): boolean {
    return content.includes("```markdown") || content.includes("```\n---\n");
  }

  /** Send a message to the AI skill creator. */
  async function handleSend() {
    const text = inputValue.trim();
    if (!text || isLoading) return;

    errorText = "";
    saveSuccess = "";
    inputValue = "";
    lastAssistantHasMarkdown = false;

    // Add user message
    messages = [...messages, message("user", text)];
    scrollToBottom();

    isLoading = true;

    let aiContent = "";

    try {
      const prompt = buildContextualPrompt(text);
      await sendAgentMessage(
        { message: prompt, systemPrompt: SKILL_CREATION_PROMPT },
        {
          onStatus: () => {
            // AI is thinking — already shown via isLoading
          },
          onMessage: (data) => {
            aiContent = data.content;
          },
          onDone: () => {
            if (aiContent) {
              messages = [...messages, message("assistant", aiContent)];
              scrollToBottom();
              if (hasMarkdownBlock(aiContent)) {
                lastAssistantHasMarkdown = true;
              }
            }
          },
          onError: (data) => {
            errorText = data.message || "Unknown error from AI provider";
          },
        },
      );

      // If onDone wasn't called but we have content, add it
      if (
        aiContent &&
        messages[messages.length - 1]?.content !== aiContent
      ) {
        messages = [...messages, message("assistant", aiContent)];
        scrollToBottom();
        if (hasMarkdownBlock(aiContent)) {
          lastAssistantHasMarkdown = true;
        }
      }
    } catch (err: unknown) {
      // Tauri's invoke rejects with a STRING, not an Error, so `instanceof Error` never held
      // here and every backend failure showed as the generic fallback. It hid a real one:
      // "missing required key bypassPermissions" read as "Failed to reach AI provider", which
      // says the provider was unreachable when the call never left the app.
      const msg = err instanceof Error ? err.message : String(err ?? '').trim();
      errorText = msg || "Failed to reach AI provider";
    } finally {
      isLoading = false;
      scrollToBottom();
    }
  }

  /** Extract markdown content from the last assistant message and save as a skill. */
  async function saveSkillFromChat() {
    const lastMsg = messages.findLast(
      (m) => m.role === "assistant" && (m.content.includes("```markdown") || m.content.includes("```")),
    );
    if (!lastMsg) return;

    // Extract content between ```markdown and ``` (or ``` and ```)
    const markdownMatch = lastMsg.content.match(
      /```(?:markdown)?\n([\s\S]+?)```/,
    );
    if (!markdownMatch) return;

    const extracted = markdownMatch[1];

    try {
      const parsed = parseSkillMarkdown(extracted);
      const name = parsed.name || "Untitled Skill";

      await saveSkill({
        id: crypto.randomUUID(),
        name,
        description: parsed.description || "",
        content: extracted,
        source: "created",
        is_active: 0,
      });

      // Success message
      messages = [
        ...messages,
        message("assistant", `\u2713 Skill "${name}" saved! Find it in My Skills.`),
      ];
      lastAssistantHasMarkdown = false;
      saveSuccess = name;
      scrollToBottom();
    } catch (err: unknown) {
      const msg =
        err instanceof Error ? err.message : "Failed to save skill";
      errorText = msg;
    }
  }

  /** Reset the conversation to start creating a new skill. */
  function resetChat() {
    messages = [];
    lastAssistantHasMarkdown = false;
    saveSuccess = "";
    inputValue = "";
    errorText = "";
    isLoading = false;
  }

  /** Handle Enter key (send) and Shift+Enter (newline). */
  function handleKeydown(e: KeyboardEvent) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      handleSend();
    }
  }
</script>

<section class="skill-creator">
  <div class="chat-header">
    <span class="chat-title">Skill Creator</span>
    <div class="chat-actions">
      <button
        class="btn-ghost small"
        onclick={resetChat}
        disabled={isLoading}
        title="Start a new conversation"
      >
        New Conversation
      </button>
    </div>
  </div>

  <div class="chat-interface">
    <!-- role=log + polite: a screen reader announces each reply as it lands, without
         stealing focus from the composer mid-typing. -->
    <div
      class="chat-messages"
      bind:this={chatContainer}
      role="log"
      aria-live="polite"
      aria-label="Conversation"
    >
      {#each messages as msg (msg.id)}
        <div class="message {msg.role}">
          <div class="message-label">
            {msg.role === "user" ? "You" : "SHREK"}
          </div>
          <div class="message-content">
            <!-- Segments are plain text on purpose — see lib/chat-segments.ts. Fenced blocks
                 get <pre> styling so a generated skill is readable, with no {@html} sink. -->
            {#each splitChatSegments(msg.content) as seg, i (i)}
              {#if seg.kind === "code"}
                <pre class="code-block">{#if seg.lang}<span class="code-lang"
                    >{seg.lang}</span
                  >{/if}<code>{seg.content}</code></pre>
              {:else}
                <p class="prose">{seg.content}</p>
              {/if}
            {/each}
          </div>
        </div>
      {/each}

      {#if isLoading}
        <div class="message assistant loading">
          <div class="message-label">SHREK</div>
          <div class="message-content">
            <span class="thinking-dots">
              <span></span><span></span><span></span>
            </span>
          </div>
        </div>
      {/if}

      {#if errorText}
        <div class="message error-msg">
          <div class="message-content">{errorText}</div>
        </div>
      {/if}

      {#if lastAssistantHasMarkdown}
        <div class="save-skill-bar">
          <button class="btn-save-skill" onclick={saveSkillFromChat}>
            Save Skill
          </button>
          <span class="save-hint"
            >Save this skill to My Skills</span
          >
        </div>
      {/if}
    </div>

    <div class="chat-input-area">
      <textarea
        placeholder="Describe your skill needs... (Enter to send, Shift+Enter for newline)"
        rows="2"
        aria-label="Message"
        bind:value={inputValue}
        onkeydown={handleKeydown}
        disabled={isLoading}
      ></textarea>
      <button
        class="btn-primary small"
        onclick={handleSend}
        disabled={isLoading || !inputValue.trim()}
      >
        {isLoading ? "Thinking..." : "Send"}
      </button>
    </div>
  </div>
</section>

<style>
  .skill-creator {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-2);
    flex: 1;
    min-height: 0;
  }

  .chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 var(--spacing-1);
  }

  .chat-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.04em;
  }

  .chat-actions {
    display: flex;
    gap: var(--spacing-1);
  }

  .btn-ghost {
    background: none;
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    padding: 4px 10px;
    border-radius: var(--radius-sm);
    font-size: 0.78rem;
    cursor: pointer;
    transition: all 0.15s ease;
  }

  .btn-ghost:hover:not(:disabled) {
    background-color: var(--bg-card);
    color: var(--text-primary);
    border-color: var(--text-secondary);
  }

  .btn-ghost:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }

  .chat-interface {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    background-color: var(--bg-primary);
    overflow: hidden;
  }

  .chat-messages {
    flex: 1;
    padding: var(--spacing-3);
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: var(--spacing-3);
    min-height: 0;
  }

  /* Message bubbles */
  .message {
    max-width: 85%;
    padding: var(--spacing-2) var(--spacing-3);
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    line-height: 1.5;
  }

  .message.user {
    align-self: flex-end;
    background-color: var(--color-primary);
    color: var(--color-primary-text);
    border-bottom-right-radius: 4px;
  }

  .message.user .message-label {
    color: rgba(255, 255, 255, 0.7);
  }

  .message.assistant {
    align-self: flex-start;
    background-color: var(--bg-card);
    border: 1px solid var(--border-color);
    border-bottom-left-radius: 4px;
  }

  .message.error-msg {
    align-self: center;
    background-color: var(--color-danger-bg);
    border: 1px solid var(--color-danger);
    color: var(--color-danger);
    max-width: 95%;
    font-size: 0.85rem;
  }

  .message-label {
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 2px;
    color: var(--text-secondary);
  }

  .message-content {
    word-break: break-word;
  }

  /* Prose keeps the model's own line breaks; margins only between stacked segments. */
  .prose {
    white-space: pre-wrap;
    margin: 0;
  }

  .prose + .prose,
  .code-block + .prose,
  .prose + .code-block {
    margin-top: var(--spacing-2);
  }

  /* Fenced blocks: scroll sideways rather than forcing the bubble wide. */
  .code-block {
    position: relative;
    margin: 0;
    padding: var(--spacing-2);
    background-color: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-sm);
    overflow-x: auto;
    font-size: 0.82rem;
    line-height: 1.45;
  }

  .code-block code {
    font-family: var(--font-mono, monospace);
    white-space: pre;
  }

  .code-lang {
    display: block;
    margin-bottom: 4px;
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--text-secondary);
  }

  /* Save Skill bar */
  .save-skill-bar {
    display: flex;
    align-items: center;
    gap: var(--spacing-2);
    padding: var(--spacing-2) var(--spacing-3);
    background-color: var(--color-success-bg);
    border: 1px solid var(--color-success);
    border-radius: var(--radius-md);
    align-self: flex-start;
  }

  .btn-save-skill {
    background-color: var(--color-success);
    color: var(--color-primary-text);
    border: none;
    padding: 6px 16px;
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: opacity 0.15s ease;
  }

  .btn-save-skill:hover {
    opacity: 0.9;
  }

  .save-hint {
    font-size: 0.78rem;
    color: var(--text-secondary);
  }

  /* Thinking dots animation */
  .thinking-dots {
    display: inline-flex;
    gap: 4px;
    align-items: center;
    height: 1.2em;
  }

  .thinking-dots span {
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: var(--text-secondary);
    animation: dot-bounce 1.2s ease-in-out infinite;
  }

  .thinking-dots span:nth-child(2) {
    animation-delay: 0.2s;
  }

  .thinking-dots span:nth-child(3) {
    animation-delay: 0.4s;
  }

  @keyframes dot-bounce {
    0%,
    80%,
    100% {
      opacity: 0.3;
      transform: scale(0.8);
    }
    40% {
      opacity: 1;
      transform: scale(1);
    }
  }

  /* Input area */
  .chat-input-area {
    padding: var(--spacing-2);
    border-top: 1px solid var(--border-color);
    background-color: var(--bg-sidebar);
    display: flex;
    gap: var(--spacing-2);
    flex-direction: column;
  }

  textarea {
    width: 100%;
    box-sizing: border-box;
    min-height: 60px;
    background-color: var(--bg-primary);
    border: 1px solid var(--border-color);
    color: var(--text-primary);
    border-radius: var(--radius-md);
    padding: var(--spacing-2);
    font-family: var(--font-body);
    font-size: 0.9rem;
    resize: vertical;
  }

  /* Grow with the text, capped so a long prompt can't swallow the transcript. Native, so no
     ResizeObserver — but the manual drag handle stays wherever field-sizing is unsupported. */
  @supports (field-sizing: content) {
    textarea {
      field-sizing: content;
      max-height: 40vh;
      overflow-y: auto;
      resize: none;
    }
  }

  textarea:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 2px var(--bg-active);
  }

  textarea:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  .btn-primary {
    align-self: flex-end;
    padding: 6px 16px;
    border-radius: var(--radius-sm);
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
    background-color: var(--color-primary);
    color: var(--color-primary-text);
    transition: opacity 0.15s ease;
  }

  .btn-primary:hover:not(:disabled) {
    opacity: 0.9;
  }

  .btn-primary:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }
</style>
