#!/bin/bash
set -e

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
MEMORY_DIR="$(dirname "$SCRIPT_DIR")"
VENV_DIR="$MEMORY_DIR/.venv"

echo "Creating virtual environment..."
python3 -m venv "$VENV_DIR"

echo "Installing LightRAG..."
"$VENV_DIR/bin/pip" install lightrag-hku

echo "Verifying Ollama connectivity..."
curl -s localhost:11434/api/tags > /dev/null || { echo "ERROR: Ollama is not running. Start with: ollama serve"; exit 1; }

echo "Creating working directory..."
mkdir -p "$MEMORY_DIR/lightrag_workdir"

echo "Setup complete. Run index_reflection.py to index session reflections."
echo "Scripts will use the venv at: $VENV_DIR"
