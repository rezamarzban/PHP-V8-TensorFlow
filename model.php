<?php

function insert($jsfile){
return file_get_contents($jsfile);
}

$isolate = new \V8\Isolate();
$context = new \V8\Context($isolate);

$source = new \V8\StringValue($isolate, " 
window = this;

var console =  {};
console.log = function () {};
console.warn = function () {};
console.error = function () {};
 
".insert("tensorflow.js")." 

const model = tf.sequential();
model.add(tf.layers.conv2d({filters: 16, kernelSize: [3, 3], activation: 'relu', padding: 'same', inputShape: [28, 28, 1]}));
model.add(tf.layers.maxPooling2d({pool_size: [2, 2]}));
model.add(tf.layers.flatten({}));
model.add(tf.layers.dense({units: 32, activation: 'relu'}));
model.add(tf.layers.dense({units: 10, activation: 'softmax'}));
model.summary();
model.compile({optimizer: 'adam', loss: 'categoricalCrossentropy', metrics: ['accuracy'], });
 'Hello Tensorflow! Model is created ...' ");

$script = new \V8\Script($context, $source);

$result = $script->run($context);
echo $result->value(), PHP_EOL;
?>
