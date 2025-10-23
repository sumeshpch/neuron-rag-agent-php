<?php

/**
 * Chat Script
 * 
 * Interactive chat interface for the RAG-powered Knowledge Bot.
 * Ask questions and get answers based on your loaded knowledge base.
 * 
 * Usage:
 *   php scripts/chat.php
 *   php scripts/chat.php "Your question here"
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Neuron\KnowledgeBot;
use NeuronAI\Chat\Messages\UserMessage;

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

echo "🤖 Neuron ADK Knowledge Bot\n";
echo "=" . str_repeat("=", 50) . "\n\n";

try {
    // Initialize the bot
    $bot = KnowledgeBot::make();
    
    // Check if a question was provided as an argument
    $question = isset($argv[1]) ? $argv[1] : null;
    
    if ($question) {
        // Single question mode
        askQuestion($bot, $question);
    } else {
        // Interactive mode
        echo "💡 Interactive mode - Type your questions (or 'quit' to exit)\n";
        echo "   Examples:\n";
        echo "   - What is Neuron ADK?\n";
        echo "   - How do I create an agent?\n";
        echo "   - What are the key features?\n";
        echo "\n";
        
        while (true) {
            echo "\n❓ You: ";
            $question = trim(fgets(STDIN));
            
            if (empty($question)) {
                continue;
            }
            
            if (in_array(strtolower($question), ['quit', 'exit', 'q'])) {
                echo "\n👋 Goodbye!\n";
                break;
            }
            
            askQuestion($bot, $question);
        }
    }
    
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
    echo "📝 Stack trace:\n" . $e->getTraceAsString() . "\n";
    echo "\n💡 Make sure you've:\n";
    echo "   1. Run 'composer install'\n";
    echo "   2. Created a .env file with your API keys\n";
    echo "   3. Run 'php scripts/load_knowledge.php' to load knowledge\n";
    exit(1);
}

/**
 * Ask a question to the bot and display the response
 */
function askQuestion(KnowledgeBot $bot, string $question): void
{
    echo "\n🤖 Bot: ";
    echo "Thinking...\r";
    
    try {
        $startTime = microtime(true);
        
        // Send the question to the bot
        $response = $bot->chat(new UserMessage($question));
        
        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);
        
        // Clear the "Thinking..." line
        echo str_repeat(" ", 50) . "\r";
        
        // Display the response
        echo "🤖 Bot: " . $response->getContent() . "\n";
        echo "\n⏱️  Response time: {$duration}s\n";
        
    } catch (Exception $e) {
        echo "\n❌ Error getting response: " . $e->getMessage() . "\n";
    }
}

