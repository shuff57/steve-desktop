describe('vitest setup', () => {
  it('vitest is configured correctly', () => {
    expect(1 + 1).toBe(2);
  });

  it('globals are available', () => {
    expect(typeof describe).toBe('function');
    expect(typeof it).toBe('function');
    expect(typeof expect).toBe('function');
  });
});
