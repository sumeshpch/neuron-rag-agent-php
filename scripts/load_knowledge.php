<?php

/**
 * Load Knowledge Script
 * 
 * This script loads documents from the knowledge directory into the RAG agent's
 * vector store. Run this script whenever you add or update knowledge base files.
 * 
 * Usage:
 *   php scripts/load_knowledge.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Neuron\KnowledgeBot;
use NeuronAI\RAG\DataLoader\FileDataLoader;

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

echo "🚀 Starting knowledge base loading...\n\n";

try {
    // Initialize the bot
    $bot = KnowledgeBot::make();
    
    // Define the knowledge directory
    $knowledgeDir = __DIR__ . '/../knowledge';
    
    if (!is_dir($knowledgeDir)) {
        throw new Exception("Knowledge directory not found: {$knowledgeDir}");
    }
    
    // Find all markdown and text files in the knowledge directory
    $files = glob($knowledgeDir . '/*.{md,txt}', GLOB_BRACE);
    
    if (empty($files)) {
        echo "⚠️  No knowledge files found in {$knowledgeDir}\n";
        echo "   Supported formats: .md, .txt\n";
        exit(1);
    }
    
    echo "📚 Found " . count($files) . " knowledge file(s):\n";
    foreach ($files as $file) {
        echo "   - " . basename($file) . "\n";
    }
    echo "\n";
    
    // Load each file
    $totalDocuments = 0;
    foreach ($files as $file) {
        echo "📖 Processing: " . basename($file) . "...\n";
        
        // Load the file using FileDataLoader
        $documents = FileDataLoader::for($file)->getDocuments();
        
        // Add documents to the bot's vector store
        $bot->addDocuments($documents);
        
        $count = count($documents);
        $totalDocuments += $count;
        echo "   ✅ Loaded {$count} document chunk(s)\n";
    }
    
    echo "\n✨ Successfully loaded {$totalDocuments} document chunk(s) into the vector store!\n";
    echo "🎯 Your knowledge bot is now ready to answer questions.\n\n";
    echo "💡 Try running: php scripts/chat.php\n";
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "📝 Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

