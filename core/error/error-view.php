<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Snappy error detail</title>
    <style>
        :root {
            --error-bg: #fff5f5;
            --error-border: #f29999;
            --error-accent: #d93025;
            --ink: #1f2328;
            --muted: #333;
            --code-bg: #fff;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --error-bg: #2b1717;
                --error-border: #7a2a2a;
                --error-accent: #ff6b6b;
                --ink: #e6e6e6;
                --muted: #a9adb4;
                --code-bg: #201a1a;
            }
        }

        body {
            margin: 0;
            padding: 1.25rem;
            background: transparent;
            color: var(--ink);
            font: 14px/1.6 system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Arial;
        }

        .error-box {
            max-width: 960px;
            margin: 0 auto;
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-top: 6px solid var(--error-accent);
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
            overflow: hidden;
        }

        .error-head {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .9rem 1.1rem;
            background: linear-gradient(to right, rgba(217, 48, 37, 0.08), transparent);
            border-bottom: 1px solid var(--error-border);
        }

        .badge {
            font-weight: 700;
            letter-spacing: .4px;
            color: #fff;
            background: var(--error-accent);
            border-radius: 999px;
            padding: .25rem .6rem;
            font-size: .85rem;
        }

        .title {
            font-weight: 700;
            color: var(--error-accent);
        }

        .meta {
            margin-left: auto;
            font-size: .85rem;
            color: var(--muted);
        }

        .error-body {
            padding: 1rem 1.1rem 1.25rem;
        }

        .columns {
            display: flex;
            flex-wrap: wrap;
            gap: 1.25rem;
            justify-content: space-between
        }

        .grid {
            /* flex: 1 1 45%; */
            width: 47%;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .grid2 {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .row {
            display: flex;
            /* justify-content: space-between; */
            align-items: flex-start;
            gap: .75rem;
        }

        .label {
            /* flex: 0 0 30%; */
            width: 15%;
            color: var(--muted);
        }

        .value {
            /* flex: 0 0 70%; */
            font-weight: 600;
            word-break: break-word;
            width: 85%;
        }

        code,
        pre {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            overflow-wrap: break-word;
            word-break: break-all;
        }

        .inline-code {
            display: inline-block;
            padding: .1rem .35rem;
            border-radius: 6px;
            background: var(--code-bg);
            border: 1px solid var(--error-border);
            font-size: .9em;
        }

        .file {
            display: block;
            font-size: .95em;
            color: var(--muted);
        }

        details {
            margin-top: 1rem;
            border-top: 1px dashed var(--error-border);
            padding-top: .8rem;
        }

        summary {
            cursor: pointer;
            user-select: none;
            font-weight: 700;
            color: var(--ink);
        }

        ol.trace {
            margin: .5rem 0 0 1rem;
            padding: 0 0 0 0.7rem;
        }

        ol.trace li{
            padding-left: 8px
        }

        @media(max-width: 767px) {
            .grid {
                flex: 1 1 100%;
            }

            .row {
                flex-direction: column;
            }

            .label,
            .value {
                flex: 100%;
            }
            ol.trace{
                margin-left: 1rem;
            }
        }
    </style>
</head>

<body>
    <?php 
        $post = $_POST;
        $hasDetail = count($_POST["result"]);        
    ?>
    <div class="error-box" role="alert" aria-live="assertive">
        <div class="error-head">
            <span class="badge"><?php echo $post["status"]?></span>
            <span class="title"><?php echo $post["message"]?></span>
            <span class="meta"><?php echo $post["time"] ?></span>
        </div>

        <div class="error-body">
            <?php
                $detail = $post["result"];
                if ($hasDetail > 1){
            ?>
            <div class="columns">
                <!-- Left column -->
                <div class="grid">
                    <div class="row">
                        <div class="label">Code</div>
                        <div class="value"><span class="inline-code"><?=$detail["code"]?></span></div>
                    </div>
                    <div class="row">
                        <div class="label">Message</div>
                        <div class="value"><?=$detail["message"]?></div>
                    </div>
                    <div class="row">
                        <div class="label">File</div>
                        <div class="value"><span class="file"><?=$detail["file"]?></span></div>
                    </div>
                    <div class="row">
                        <div class="label">Line</div>
                        <div class="value"><span class="inline-code"><?=$detail["line"]?></span></div>
                    </div>
                </div>

                <!-- Right column -->
                <div class="grid">
                    <div class="row">
                        <div class="label">Method</div>
                        <div class="value"><span class="inline-code"><?=$detail["method"] ?? "N/A"?></span></div>
                    </div>
                    <div class="row">
                        <div class="label">Route</div>
                        <div class="value"><span class="inline-code"><?=$detail["route"]?></span></div>
                    </div>
                    <div class="row">
                        <div class="label">Path</div>
                        <div class="value"><?=$detail["path"] ?? "N/A"?></div>
                    </div>
                </div>
            </div>
            <?php }else{ ?>
                <div class="columns">
                    <div class="grid2">
                        <div class="row">
                            <div class="value"><?=$detail["message"]?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if ($hasDetail > 1){ ?>
            <details open>
                <summary>Stack trace</summary>
                <ol class="trace">
                    <?php foreach($detail["trace"] as $key => $value){ ?>
                    <li><code><?=$value?></code></li>
                    <?php } ?>
                </ol>
            </details>
            <?php } ?>
        </div>
    </div>

</body>

</html>
