# NoSaladNet...


... is an anagram of "Standalone" so this repo contains classes
that can be used on their own without big bad complex framework behind it



## pQuery - Create HTML like in jQuery

The code

```
$pForm = new pQuery('form');

$pForm
    ->method('POST')
    ->action('?foo')
   
    ->append("Enter your <b>name</b>:")
   
    ->append(
        $pForm('input')
            ->name('bar')
            ->id('baz')
    )
   
    ->append(
        $pForm('input')
            ->type('submit')
            ->value('Done!')
    );

echo $pForm;
```

Will create this output (without formatting):

```
<form method="POST" action="?foo">
    Enter your <b>name</b>:
    <input name="bar" id="baz" />
    <input type="submit" value="Done!" />
</form>
```


## CSV - Rearrange and rename fields

Open a CSV but just use some fields and even rename them:

```
$csv = new Csv('my.csv');

// tell what you need and how to name it

$csv->fieldMap = array(
    'original_name' => 'new_name',
    'you_can_even' => 'rearrange_with_that',
    'second' => 'second',
    'first' => 'first, 
);
```


## Cache - KISS

```
$cache = new Cache('some/folder', 3600 /* seconds */);

if (!$cache->getData('foo')) {
    $cache->setData('foo', $someTimeConsumingMess);
}
```

No need for serialization. The class has it's own strategy.
