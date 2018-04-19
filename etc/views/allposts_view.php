<article>
<header>
<h2>All posts</h2>
</header>
  <?php
  if (!empty($data['Allposts'])) {

    $_timepost = null;
    $_section = null;

    foreach ($data['Allposts'] as $value) {

      if ($value['PostDate'] != $_timepost) {
        echo $_section;
        echo '<section class="third"><div>' . $value['PostDate'] . '</div><div class="listbookcases"><ul class="shelvenav">';
        # this section will close all post in same date
        $_section = '</ul></div></section>';
        $_timepost = $value['PostDate'];
      } else {
        $_timepost = $value['PostDate'];
      }

      echo '<li><a href="/setuppost?idpt='.$value['Record'].'">' . $value['PostName'] . '</a></li><br>';
    }
    echo $_section;
  } ?>
</article>
<footer>
<div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
