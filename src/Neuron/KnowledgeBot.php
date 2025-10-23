<?php

namespace App\Neuron;

use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\Anthropic\Anthropic;
use NeuronAI\Providers\OpenAI\OpenAI;
use NeuronAI\RAG\Embeddings\EmbeddingsProviderInterface;
use NeuronAI\RAG\Embeddings\OpenAIEmbeddingProvider;
use NeuronAI\RAG\RAG;
use NeuronAI\RAG\VectorStore\FileVectorStore;
use NeuronAI\RAG\VectorStore\VectorStoreInterface;

/**
 * KnowledgeBot - A RAG-powered chatbot that can answer questions
 * based on your custom knowledge base.
 */
class KnowledgeBot extends RAG
{
    /**
     * Configure the AI provider (LLM)
     * 
     * Dynamically selects the provider based on AI_PROVIDER environment variable.
     * Supported providers:
     * - openai: OpenAI (GPT models)
     * - anthropic: Anthropic (Claude models)
     * - google: Google (Gemini models)
     * - ollama: Ollama (local models)
     */
    protected function provider(): AIProviderInterface
    {
        $provider = $_ENV['AI_PROVIDER'] ?? getenv('AI_PROVIDER') ?: 'anthropic';
        
        return match(strtolower($provider)) {
            'openai' => new OpenAI(
                key: $_ENV['OPENAI_API_KEY'] ?? getenv('OPENAI_API_KEY'),
                model: $_ENV['OPENAI_MODEL'] ?? getenv('OPENAI_MODEL') ?: 'gpt-4-turbo-preview',
            ),
            'anthropic' => new Anthropic(
                key: $_ENV['ANTHROPIC_API_KEY'] ?? getenv('ANTHROPIC_API_KEY'),
                model: $_ENV['ANTHROPIC_MODEL'] ?? getenv('ANTHROPIC_MODEL') ?: 'claude-3-5-sonnet-20241022',
            ),
            default => throw new \InvalidArgumentException(
                "Unsupported AI provider: {$provider}. Supported providers: openai, anthropic"
            ),
        };
    }
    
    /**
     * Configure the embeddings provider
     * 
     * Embeddings convert text into vector representations
     * that can be mathematically compared for similarity.
     */
    protected function embeddings(): EmbeddingsProviderInterface
    {
        return new OpenAIEmbeddingProvider(
            key: $_ENV['OPENAI_API_KEY'] ?? getenv('OPENAI_API_KEY'),
            model: $_ENV['OPENAI_EMBEDDING_MODEL'] ?? getenv('OPENAI_EMBEDDING_MODEL') ?: 'text-embedding-3-small'
        );
    }
    
    /**
     * Configure the vector store
     * 
     * The vector store persists document embeddings and performs
     * semantic similarity searches to retrieve relevant context.
     */
    protected function vectorStore(): VectorStoreInterface
    {
        $storageDir = $_ENV['VECTOR_STORE_DIR'] ?? getenv('VECTOR_STORE_DIR') ?: __DIR__ . '/../../storage/vectors';
        
        // Ensure the directory exists
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        
        return new FileVectorStore(
            directory: $storageDir,
            key: 'knowledge_bot'
        );
    }
}

