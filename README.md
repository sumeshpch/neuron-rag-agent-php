# Neuron ADK - RAG Agent POC

A proof of concept demonstrating how to build a **RAG (Retrieval Augmented Generation)** agent using [Neuron AI ADK for PHP](https://github.com/inspector-apm/neuron-ai). This project showcases how to create an intelligent chatbot that can answer questions based on your custom knowledge base.

## 🎯 What This POC Demonstrates

- **RAG Architecture**: Combines retrieval from a vector store with LLM generation
- **Vector Embeddings**: Converts text into mathematical representations for semantic search
- **Multiple AI Providers**: Shows how to integrate with Anthropic (Claude) and OpenAI
- **Knowledge Management**: Load and index documents for intelligent retrieval
- **Production-Ready Pattern**: Clean architecture following best practices

## 🏗️ Architecture Overview

```
┌─────────────────┐
│  User Question  │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────┐
│    Knowledge Bot (RAG Agent)    │
│  ┌──────────────────────────┐   │
│  │  1. Embed the question   │   │
│  └──────────┬───────────────┘   │
│             ▼                   │
│  ┌──────────────────────────┐   │
│  │  2. Search vector store  │   │
│  │     for similar docs     │   │
│  └──────────┬───────────────┘   │
│             ▼                   │
│  ┌──────────────────────────┐   │
│  │  3. Retrieve relevant    │   │
│  │     context chunks       │   │
│  └──────────┬───────────────┘   │
│             ▼                   │
│  ┌──────────────────────────┐   │
│  │  4. Send context + query │   │
│  │     to LLM               │   │
│  └──────────┬───────────────┘   │
│             ▼                   │
│  ┌──────────────────────────┐   │
│  │  5. Generate response    │   │
│  └──────────────────────────┘   │
└─────────────────────────────────┘
         │
         ▼
┌─────────────────┐
│    Response     │
└─────────────────┘
```

## 📋 Prerequisites

- **PHP 8.1 or higher**
- **Composer** (PHP dependency manager)
- **API Keys**:
  - Anthropic API key (for Claude) - [Get one here](https://console.anthropic.com/)
  - OpenAI API key (for embeddings) - [Get one here](https://platform.openai.com/api-keys)

## 🚀 Quick Start

### 1. Install Dependencies

```bash
composer install
```

### 2. Configure Environment

Copy the example environment file and add your API keys:

```bash
cp .env.example .env
```

Edit `.env` and add your API keys:

```env
ANTHROPIC_API_KEY=your_anthropic_api_key_here
OPENAI_API_KEY=your_openai_api_key_here
```

### 3. Load Knowledge Base

Load the sample knowledge into the vector store:

```bash
php scripts/load_knowledge.php
```

This will:
- Read all `.md` and `.txt` files from the `knowledge/` directory
- Generate embeddings for each document chunk
- Store them in the vector store for later retrieval

### 4. Start Chatting

Run the interactive chat interface:

```bash
php scripts/chat.php
```

Or ask a single question:

```bash
php scripts/chat.php "What is Neuron ADK?"
```

## 📁 Project Structure

```
neuronADK/
├── composer.json              # PHP dependencies
├── .env.example              # Environment configuration template
├── .gitignore               # Git ignore rules
├── README.md                # This file
│
├── src/
│   └── Neuron/
│       └── KnowledgeBot.php # Main RAG agent class
│
├── scripts/
│   ├── load_knowledge.php   # Script to load documents
│   └── chat.php            # Interactive chat interface
│
├── knowledge/              # Your knowledge base files
│   └── sample_knowledge.md # Sample documentation
│
└── storage/               # Generated data (not in git)
    └── vectors/          # Vector embeddings storage
```

## 📚 Adding Your Own Knowledge

1. Create new `.md` or `.txt` files in the `knowledge/` directory
2. Add your content (documentation, FAQs, guides, etc.)
3. Run the loader: `php scripts/load_knowledge.php`
4. Start asking questions: `php scripts/chat.php`

### Supported Formats

- **Markdown** (`.md`) - Perfect for documentation
- **Text** (`.txt`) - Plain text files
- **PDF** - Requires additional data loader
- **Database** - Can be implemented with custom loaders

## 🎨 Example Questions to Try

Based on the sample knowledge base:

```bash
php scripts/chat.php "What is Neuron ADK?"
php scripts/chat.php "How do I create an agent?"
php scripts/chat.php "What are the key features?"
php scripts/chat.php "How does RAG work?"
php scripts/chat.php "What AI providers are supported?"
php scripts/chat.php "How do vector embeddings work?"
php scripts/chat.php "What are the best practices?"
```

## 🔑 Understanding Key Concepts

### What is RAG?

**RAG** stands for **Retrieval Augmented Generation**:

- **Retrieval**: Find relevant information from your knowledge base
- **Augmented**: Enhance the AI's knowledge with external data
- **Generation**: Create responses using both training data and retrieved context

### Why Use RAG?

1. **Up-to-date Information**: Access current data not in the model's training
2. **Domain-Specific Knowledge**: Add proprietary or specialized information
3. **Reduced Hallucinations**: Ground responses in factual documents
4. **Cost-Effective**: No need to fine-tune models
5. **Easy Updates**: Simply add new documents to update knowledge

### Vector Embeddings Explained

Embeddings convert text into numbers (vectors) that capture semantic meaning:

```
"AI Agent"        →  [0.12, -0.45, 0.89, ...]
"Chatbot"         →  [0.15, -0.42, 0.87, ...]  ← Similar vectors!
"Database Query"  →  [-0.67, 0.23, -0.11, ...] ← Different vector
```

Similar concepts have similar vectors, enabling semantic search.

## 🛠️ Configuration Options

### Change AI Provider

The bot automatically selects the AI provider based on the `AI_PROVIDER` environment variable in your `.env` file:

```env
# Use Anthropic (Claude)
AI_PROVIDER=anthropic
ANTHROPIC_API_KEY=your_key_here
ANTHROPIC_MODEL=claude-3-5-sonnet-20241022

# OR use OpenAI (GPT)
AI_PROVIDER=openai
OPENAI_API_KEY=your_key_here
OPENAI_MODEL=gpt-4-turbo-preview
```

**Supported providers:**
- `anthropic` - Anthropic Claude (default)
- `openai` - OpenAI GPT models

No code changes needed! Just update your `.env` file and restart the bot.

### Adjust Embedding Model

Choose different embedding models for speed/accuracy tradeoffs:

```php
// Faster, cheaper
model: 'text-embedding-3-small'

// More accurate, expensive
model: 'text-embedding-3-large'
```

### Vector Store Options

Neuron supports multiple vector store backends:

- `FileVectorStore` - File-based (good for POC/development)
- `PineconeVectorStore` - Managed vector DB (production)
- `ChromaVectorStore` - Open-source vector DB
- Custom implementations

## 📊 Monitoring & Observability

Neuron ADK includes built-in observability through [Inspector](https://inspector.dev):

- Track LLM calls and costs
- Monitor retrieval effectiveness
- Debug agent behavior
- Analyze response times
- View token usage

## 🚧 Production Considerations

Before deploying to production:

1. **Error Handling**: Add try-catch blocks and fallback strategies
2. **Rate Limiting**: Implement API call throttling
3. **Caching**: Cache frequent queries
4. **Security**: Secure API keys, validate inputs
5. **Monitoring**: Set up logging and alerts
6. **Vector Store**: Consider managed solutions (Pinecone, Weaviate)
7. **Chunking Strategy**: Optimize document splitting for your use case
8. **Cost Management**: Monitor token usage and API costs

## 📖 Resources

- **Neuron AI GitHub**: https://github.com/inspector-apm/neuron-ai
- **Documentation**: https://docs.neuron-ai.dev
- **Newsletter**: https://neuron-ai.dev
- **E-Book**: [Start With AI Agents In PHP](https://www.amazon.com/dp/B0F1YX8KJB)

## 🤝 Contributing

This is a POC project. Feel free to:

- Add more example knowledge bases
- Implement additional data loaders
- Add web interface
- Integrate different vector stores
- Improve error handling
- Add tests

## 📝 License

This POC is provided as-is for educational and demonstration purposes.

## 🐛 Troubleshooting

### "Class not found" errors

Run: `composer install`

### "API key not found" errors

Make sure you've:
1. Copied `.env.example` to `.env`
2. Added your actual API keys
3. The `.env` file is in the project root

### No relevant answers

Make sure you've loaded the knowledge:
```bash
php scripts/load_knowledge.php
```

### "Vector store empty" warnings

The bot works but has no custom knowledge. Load documents first.

## 💡 Next Steps

1. **Add Your Content**: Replace sample knowledge with your own documents
2. **Customize Behavior**: Modify the bot's system prompts and parameters
3. **Add Features**: Implement conversation history, context management
4. **Scale Up**: Use production vector stores (Pinecone, Weaviate)
5. **Build UI**: Create a web interface or API endpoint
6. **Monitor**: Set up Inspector for production monitoring

---

**Built with** [Neuron AI ADK](https://github.com/inspector-apm/neuron-ai) - The Agent Development Kit for PHP

