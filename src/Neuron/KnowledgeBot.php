<?php

namespace App\Neuron;

use NeuronAI\Providers\AIProviderInterface;
use NeuronAI\Providers\Anthropic\Anthropic;
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
     * This example uses Anthropic's Claude, but you can use any supported provider:
     * - OpenAI
     * - Anthropic
     * - Google (Gemini)
     * - Ollama (for local models)
     */
    protected function provider(): AIProviderInterface
    {
        return new Anthropic(
            key: $_ENV['ANTHROPIC_API_KEY'] ?? getenv('ANTHROPIC_API_KEY'),
            model: $_ENV['ANTHROPIC_MODEL'] ?? getenv('ANTHROPIC_MODEL') ?: 'claude-3-5-sonnet-20241022',
        );
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

