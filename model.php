<?php

function insertScriptFromFile($jsfile){
    return file_get_contents($jsfile);
}

use V8\{
    Isolate,
    Context,
    StringValue,
    Script,
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
 'Hello Tensorflow! Model is created ...'";

$isolate = new Isolate();
$context = new Context($isolate);
$source = new StringValue($isolate, $script_source_string);
$script = new Script($context, $source);

$result = $script->run($context);
echo $result->value(), PHP_EOL;
?>

/*
*
* @author Reza Marzban
*
* Ver 0.1.01
*
* GitHub.com/marzban2030/PHP-V8-TensorFlow
*
*/
