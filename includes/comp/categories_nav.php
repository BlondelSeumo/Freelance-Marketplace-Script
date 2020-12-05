<div data-ui="cat-nav" id="desktop-category-nav" class="ui-toolkit cat-nav ">
  <div class="bg-white bg-transparent-homepage-experiment bb-xs-1 hide-xs hide-sm hide-md">
    <div class="col-group body-max-width">
      <ul class="col-xs-12 body-max-width display-flex-xs justify-content-space-between" role="menubar" data-ui="top-nav-category-list" aria-activedescendant="catnav-primary-link-10855">
        <?php
        $get_categories = $db->query("select * from categories where cat_featured='yes'".($lang_dir=="right"?'order by 1 DESC':'')." LIMIT 0,9");
        while($row_categories = $get_categories->fetch()){
        $cat_id = $row_categories->cat_id;
        $cat_url = $row_categories->cat_url;
        $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id,"language_id" => $siteLanguage));
        $row_meta = $get_meta->fetch();
        @$cat_title = $row_meta->cat_title;
        ?>
        <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" 
          data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-<?= $cat_id; ?>">
          <a href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
          <?= @$cat_title; ?>
          </a>
        </li>
        <?php } ?>

        <?php

        $count = $db->count("categories",array("cat_featured" => "yes"));
        if($count > 10){

        ?>

        <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" 
          data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-more">
          <a href="#"><?= $lang['more']; ?></a>
        </li>

        <?php 

        }else{ 

        $get_categories = $db->query("select * from categories where cat_featured='yes'".($lang_dir=="right"?'order by 1 DESC':'')." LIMIT 9,1");
        while($row_categories = $get_categories->fetch()){
        $cat_id = $row_categories->cat_id;
        $cat_url = $row_categories->cat_url;
        $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id,"language_id" => $siteLanguage));
        $row_meta = $get_meta->fetch();
        @$cat_title = $row_meta->cat_title;

        ?>

        <li class="top-nav-item pt-xs-1 pb-xs-1 pl-xs-2 pr-xs-2 display-flex-xs align-items-center text-center" 
          data-linkable="true" data-ui="top-nav-category-link" data-node-id="c-<?= $cat_id; ?>">
          <a href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
          <?= @$cat_title; ?>
          </a>
        </li>

        <?php } } ?>

      </ul>
    </div>
  </div>

  <div class="position-absolute col-xs-12 col-centered z-index-4">
    <div>
      <?php
      $get_categories = $db->query("select * from categories where cat_featured='yes'".($lang_dir=="right"?'order by 1 DESC':'')." LIMIT 0,10");
      while($row_categories = $get_categories->fetch()){
      $cat_id = $row_categories->cat_id;
      $cat_url = $row_categories->cat_url;
      $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id,"language_id" => $siteLanguage));
      $row_meta = $get_meta->fetch();
      @$cat_title = $row_meta->cat_title;
      $count = $db->count("categories_children",array("child_parent_id" => $cat_id));
      if($count > 0){
      ?>
      <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden bl-xs-1 bb-xs-1 br-xs-1 catnav-mott-control display-none" data-ui="sub-nav" aria-hidden="true" data-node-id="c-<?= $cat_id; ?>">
        <div class="width-full display-flex-xs">
          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
            <?php
              $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 0,10");
              while($row_child_cat = $get_child_cat->fetch()){
              $child_id = $row_child_cat->child_id;
              $child_url = $row_child_cat->child_url;
              $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
              $row_meta = $get_meta->fetch();
              $child_title = $row_meta->child_title;
              ?>
            <li>
              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
              <?= $child_title; ?>
              </a>
            </li>
            <?php } ?>
          </ul>
          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
            <?php
            $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 10,10");
            while($row_child_cat = $get_child_cat->fetch()){
            $child_id = $row_child_cat->child_id;
            $child_url = $row_child_cat->child_url;
            $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
            $row_meta = $get_meta->fetch();
            $child_title = $row_meta->child_title;
            ?>
            <li>
              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                <?= $child_title; ?>
              </a>
            </li>
            <?php } ?>
          </ul>
          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
            <?php
            $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 20,10");
            while($row_child_cat = $get_child_cat->fetch()){
            $child_id = $row_child_cat->child_id;
            $child_url = $row_child_cat->child_url;
            $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
            $row_meta = $get_meta->fetch();
            $child_title = $row_meta->child_title;

            ?>
            <li>
              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                <?= $child_title; ?>
              </a>
            </li>
            <?php }?>
          </ul>
          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">
            <?php
            $get_child_cat = $db->query("select * from categories_children where child_parent_id='$cat_id' LIMIT 30,10");
            while($row_child_cat = $get_child_cat->fetch()){
            $child_id = $row_child_cat->child_id;
            $child_url = $row_child_cat->child_url;
            $get_meta = $db->select("child_cats_meta",array("child_id" => $child_id, "language_id" => $siteLanguage));
            $row_meta = $get_meta->fetch();
            $child_title = $row_meta->child_title;
            ?>
            <li>
              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>/<?= $child_url; ?>">
                <?= $child_title; ?>
              </a>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
      <?php } ?>
      <?php } ?>

      <div class="body-sub-width vertical-align-top sub-nav-container bg-white overflow-hidden bl-xs-1 bb-xs-1 br-xs-1 catnav-mott-control display-none" data-ui="sub-nav" aria-hidden="true" data-node-id="c-more">

        <div class="width-full display-flex-xs">

          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">

            <?php

            $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 9,19");
            while($row_categories = $get_categories->fetch()){

            $cat_id = $row_categories->cat_id;
            $cat_url = $row_categories->cat_url;

            $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id,"language_id" => $siteLanguage));
            $row_meta = $get_meta->fetch();
            $cat_title = $row_meta->cat_title;

            ?>

            <li>
              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                <?= @$cat_title; ?>
              </a>
            </li>

            <?php } ?>

          </ul>

          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">

            <?php

            $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 19,29");
            while($row_categories = $get_categories->fetch()){

            $cat_id = $row_categories->cat_id;
            $cat_url = $row_categories->cat_url;

            $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id,"language_id" => $siteLanguage));
            $row_meta = $get_meta->fetch();
            $cat_title = $row_meta->cat_title;

            ?>

            <li>
              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                <?= @$cat_title; ?>
              </a>
            </li>

            <?php } ?>

          </ul>


          <ul class="list-unstyled display-inline-block col-xs-3 p-xs-3 pl-xs-5" role="presentation">

            <?php

            $get_categories = $db->query("select * from categories where cat_featured='yes' LIMIT 29,39");
            while($row_categories = $get_categories->fetch()){

            $cat_id = $row_categories->cat_id;
            $cat_url = $row_categories->cat_url;

            $get_meta = $db->select("cats_meta",array("cat_id" => $cat_id,"language_id" => $siteLanguage));
            $row_meta = $get_meta->fetch();
            $cat_title = $row_meta->cat_title;

            ?>

            <li>
              <a class="display-block text-gray text-body-larger pt-xs-1" href="<?= $site_url; ?>/categories/<?= $cat_url; ?>">
                <?= @$cat_title; ?>
              </a>
            </li>

            <?php } ?>

          </ul>

        </div>

      </div>

    </div>
  </div>
</div>
<?php include("mobile_menu.php"); ?>