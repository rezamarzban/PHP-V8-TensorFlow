<?php declare(strict_types=1);

function insertScriptFromFile($jsfile){
    return file_get_contents($jsfile);
}

use V8\{
    Isolate,
    Context,
    StringValue,
    ScriptCompiler,
    StartupData,
};

$script_source_string = "
window = this;

var console =  {};
console.log = function () {};
console.warn = function () {};
console.error = function () {};
 
".insertScriptFromFile("tensorflow.js")." 

const model = tf.sequential();
model.add(tf.layers.conv2d({filters: 16, kernelSize: [3, 3], activation: 'relu', padding: 'same', inputShape: [28, 28, 1]}));
model.add(tf.layers.maxPooling2d({pool_size: [2, 2]}));
model.add(tf.layers.flatten({}));
model.add(tf.layers.dense({units: 32, activation: 'relu'}));
model.add(tf.layers.dense({units: 10, activation: 'softmax'}));
model.summary();
model.compile({optimizer: 'adam', loss: 'categoricalCrossentropy', metrics: ['accuracy'], });
 'Hello Tensorflow! Model is created ...' ";

$startup_data = StartupData::createFromSource($script_source_string);

$isolate       = new Isolate($startup_data);
$context       = new Context($isolate);
$source_string = new StringValue($isolate, $script_source_string);
$source        = new ScriptCompiler\Source($source_string);

$unbound_script = ScriptCompiler::compileUnboundScript($context, $source);
$cached_data = ScriptCompiler::createCodeCache($unbound_script, $source_string);

$source = new ScriptCompiler\Source($source_string, null, $cached_data);
$script = ScriptCompiler::compile($context, $source, ScriptCompiler::OPTION_CONSUME_CODE_CACHE);

if ($cached_data->isRejected()) {
    throw new RuntimeException('Script code cache rejected!');
}

$script = ScriptCompiler::compile($context, $source);

$result = $script->run($context);

echo $result->value(), PHP_EOL;

/*
*
* @author Reza Marzban
*
* Ver 0.1.01 Beta
*
* GitHub.com/marzban2030/PHP-V8-TensorFlow
*
*/
