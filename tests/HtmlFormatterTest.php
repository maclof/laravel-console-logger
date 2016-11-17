<?php

use Illuminated\Console\Log\HtmlFormatter;

class HtmlFormatterTest extends TestCase
{
    /** @test */
    public function it_properly_formats_an_emergency_records()
    {
        $record = [
            'message' => 'Emergency!',
            'context' => [],
            'level' => 600,
            'level_name' => 'EMERGENCY',
            'channel' => 'ICLogger',
            'datetime' => new DateTime('2016-11-11 11:12:13'),
            'extra' => [],
        ];

        $this->assertOutputsEqual($this->composeExpectedOutput($record), (new HtmlFormatter)->format($record));
    }

    protected function assertOutputsEqual($output1, $output2)
    {
        $this->assertEquals($this->normalizeOutput($output1), $this->normalizeOutput($output2));
    }

    private function normalizeOutput($output)
    {
        return preg_replace('!\s+!smi', '', $output);
    }

    private function composeExpectedOutput(array $record)
    {
        return "<!DOCTYPE html>
            <html>
                <head>
                    <meta charset=\"utf-8\">
                    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
                    <style>
                        body {
                            font-family: 'Lato', sans-serif;
                            font-size: 16px;
                        }
                        .title, .subtitle {
                            color: #ffffff;
                            margin: 0px;
                            padding: 15px;
                        }
                        .title.{$record['level_name']}, .subtitle.{$record['level_name']} {
                            background: #000000;
                        }
                        .details-row {
                            text-align: left;
                            font-size: 16px;
                        }
                        .details-row-header {
                            background: #cccccc;
                            width: 150px;
                            padding: 10px;
                        }
                        .details-row-body {
                            background: #eeeeee;
                            white-space: nowrap;
                            width: 100%;
                            padding: 10px;
                        }
                    </style>
                </head>
                <body>
                    <h2 class='title {$record['level_name']}'>{$record['level_name']}</h2>
                    <style>.title { padding-bottom: 0px !important; } .subtitle { padding-top: 0px !important; }</style>
                    <h3 class='subtitle {$record['level_name']}'>This notification was sent from `TESTING` environment!</h3>
                    <table cellspacing=\"1\" width=\"100%\">
                        <tr class='details-row'>
                            <th class='details-row-header'>Message:</th>
                            <td class='details-row-body'>{$record['message']}</td>
                        </tr>
                        <tr class='details-row'>
                            <th class='details-row-header'>Time:</th>
                            <td class='details-row-body'>{$record['datetime']->format('Y-m-d H:i:s')}</td>
                        </tr>
                    </table>
                </body>
            </html>";
    }
}
