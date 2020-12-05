<?php if($count_faq > 0){ ?>
<section class="faq-wrap"><!--- faq-wrap Starts --->
<header>
<span class="ficon ficon-chevron-down"></span> <h2>Frequently Asked Questions</h2>
</header>
<ul class="faq-list">
<?php 
while($row_faq = $get_faq->fetch()){
$id = $row_faq->id;
$title = $row_faq->title;
$content = $row_faq->content;
?>
<li>
<h3 class="text-dark"><?= $title; ?> <i class="fa fa-arrow-circle-down" aria-hidden="true"></i></h3>
<p><?= $content; ?></p>
</li>
<?php } ?>
</ul>
</section><!--- faq-wrap Ends --->
<?php } ?>