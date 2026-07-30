<?php

namespace App\Services\AiAssistant;

use App\Services\AiAssistant\Tools\BlogPostTools;
use App\Services\AiAssistant\Tools\FaqTools;
use App\Services\AiAssistant\Tools\ProjectTools;
use App\Services\AiAssistant\Tools\ServiceTools;
use App\Services\AiAssistant\Tools\SettingsTools;
use App\Services\AiAssistant\Tools\TestimonialTools;
use Throwable;

class ToolRegistry
{
    /** @var array<string, array>|null */
    protected static ?array $definitions = null;

    protected static function providers(): array
    {
        return [
            SettingsTools::class,
            BlogPostTools::class,
            ServiceTools::class,
            ProjectTools::class,
            FaqTools::class,
            TestimonialTools::class,
        ];
    }

    /**
     * All tool definitions keyed by tool name.
     */
    protected static function definitions(): array
    {
        if (static::$definitions !== null) {
            return static::$definitions;
        }

        $definitions = [];

        foreach (static::providers() as $provider) {
            foreach ($provider::definitions() as $definition) {
                $definitions[$definition['name']] = $definition;
            }
        }

        return static::$definitions = $definitions;
    }

    /**
     * Tool schemas formatted for the Groq/OpenAI-compatible `tools` parameter.
     */
    public static function schemas(): array
    {
        return array_values(array_map(fn (array $d) => [
            'type' => 'function',
            'function' => [
                'name' => $d['name'],
                'description' => $d['description'],
                'parameters' => $d['input_schema'],
            ],
        ], static::definitions()));
    }

    /**
     * Execute a tool by name with the given input, always returning a JSON-encodable array.
     */
    public static function execute(string $name, array $input): array
    {
        $definitions = static::definitions();

        if (! isset($definitions[$name])) {
            return ['error' => "Unknown tool \"{$name}\"."];
        }

        try {
            return ($definitions[$name]['handler'])($input);
        } catch (Throwable $e) {
            return ['error' => 'Tool execution failed: '.$e->getMessage()];
        }
    }
}
