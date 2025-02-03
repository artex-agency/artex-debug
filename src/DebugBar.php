<?php declare(strict_types=1);

/**
 * Artex Debug - DebugBar
 * 
 * Provides a floating debug panel for web environments to display logs,
 * queries, performance benchmarks, and other debug information.
 *
 * @package    Artex\Debug
 * @version    1.0.0
 * @author     James Gober <me@jamesgober.com>
 * @license    Apache License 2.0
 * @link       https://github.com/artex-agency/artex-debug
 */

namespace Artex\Debug;

use Artex\Debug\DataCollector;
use Artex\Debug\Helpers;

/**
 * DebugBar - Floating Debug Panel
 *
 * Displays logs, errors, execution times, and other debugging data
 * in a floating debug bar within the browser.
 */
class DebugBar
{
    /**
     * Renders the debug bar if debugging is enabled.
     *
     * @param DataCollector $collector The data collector instance.
     * @return void
     */
    public function render(DataCollector $collector): void
    {
        if (Helpers::isCli() || !Config::get('debug_mode')) {
            return; // Don't render in CLI or if debug mode is disabled
        }

        $logs       = json_encode($collector->getLogs(), JSON_PRETTY_PRINT);
        $benchmarks = json_encode($collector->getBenchmarks(), JSON_PRETTY_PRINT);
        $queries    = json_encode($collector->getQueries(), JSON_PRETTY_PRINT);
        $errors     = json_encode($collector->getErrors(), JSON_PRETTY_PRINT);

        echo $this->generateHtml($logs, $benchmarks, $queries, $errors);
    }

    /**
     * Generates the HTML for the debug bar.
     *
     * @param string $logs       JSON-encoded log entries.
     * @param string $benchmarks JSON-encoded benchmark results.
     * @param string $queries    JSON-encoded database queries.
     * @param string $errors     JSON-encoded error messages.
     * @return string The generated HTML for the debug bar.
     */
    private function generateHtml(string $logs, string $benchmarks, string $queries, string $errors): string
    {
        return <<<HTML
        <div id="artex-debug-bar">
            <div id="debug-toggle">⚙️ Debug</div>
            <div id="debug-panel">
                <h3>Artex Debug Bar</h3>
                <button onclick="document.getElementById('debug-panel').style.display='none'">Close</button>
                <pre><strong>Logs:</strong> {$logs}</pre>
                <pre><strong>Benchmarks:</strong> {$benchmarks}</pre>
                <pre><strong>Queries:</strong> {$queries}</pre>
                <pre><strong>Errors:</strong> {$errors}</pre>
            </div>
        </div>
        <style>
            #artex-debug-bar {
                position: fixed;
                bottom: 10px;
                right: 10px;
                background: rgba(0,0,0,0.8);
                color: white;
                padding: 10px;
                border-radius: 5px;
                font-size: 14px;
                font-family: monospace;
                z-index: 9999;
            }
            #debug-toggle {
                cursor: pointer;
            }
            #debug-panel {
                display: none;
                background: #222;
                padding: 10px;
                border-radius: 5px;
                max-width: 400px;
                word-wrap: break-word;
                overflow-x: auto;
            }
            #debug-panel pre {
                background: #333;
                padding: 5px;
                border-radius: 3px;
                white-space: pre-wrap;
            }
        </style>
        <script>
            document.getElementById('debug-toggle').addEventListener('click', function() {
                let panel = document.getElementById('debug-panel');
                panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
            });
        </script>
        HTML;
    }
}