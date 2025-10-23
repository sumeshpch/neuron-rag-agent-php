# Quick Start Guide

Get up and running with your RAG agent in 5 minutes!

## Step-by-Step Setup

### 1️⃣ Install Dependencies

```bash
composer install
```

### 2️⃣ Configure API Keys

Create a `.env` file (copy from `env.example`):

```bash
cp env.example .env
```

Edit `.env` and add your API keys:

```env
ANTHROPIC_API_KEY=sk-ant-xxxxx
OPENAI_API_KEY=sk-xxxxx
```

**Get API Keys:**
- Anthropic: https://console.anthropic.com/
- OpenAI: https://platform.openai.com/api-keys

### 3️⃣ Load Knowledge Base

```bash
php scripts/load_knowledge.php
```

Output:
```
🚀 Starting knowledge base loading...
📚 Found 1 knowledge file(s):
   - sample_knowledge.md
📖 Processing: sample_knowledge.md...
   ✅ Loaded 15 document chunk(s)
✨ Successfully loaded 15 document chunk(s) into the vector store!
```

### 4️⃣ Start Chatting!

```bash
php scripts/chat.php
```

Try these questions:
- "What is Neuron ADK?"
- "How do I create an agent?"
- "What are the key features?"
- "Explain RAG to me"

## That's It!

Your RAG agent is now running. Check the main [README.md](README.md) for more details.

## Next Steps

1. **Add your own knowledge**: Put `.md` or `.txt` files in `knowledge/`
2. **Reload**: Run `php scripts/load_knowledge.php` again
3. **Ask questions**: Your bot now knows about your content!

## Need Help?

- Read the full [README.md](README.md)
- Check [Neuron AI Docs](https://docs.neuron-ai.dev)
- See the [tutorial](https://inspector.dev/how-to-create-a-rag-agent-with-neuron-adk-for-php/)

