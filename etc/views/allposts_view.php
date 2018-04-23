<article>
<header>
<h2>Blog Posts</h2>
</header>
  <?php
  if (!empty($data['Posts'])) {

    $_timepost = null;
    $_section = null;

    foreach ($data['Posts'] as $value) {

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
  }

  if (!empty($data['pages'])) {
      echo '<div class="pagination">';
      $_limit_less = $data['active_page'] - 4; $_limit_more = $data['active_page'] + 4;
      $_next_less = 1; $_next_more = 1; $_btngroup = null;

      for ($i = 1; $i <= $data['pages']; $i++) {
        if ($data['active_page'] == $i) {
          $_activepage = '<span class="currentpg">'. $i .'</span>';
          $_next_less = $i - 1;
          $_next_more = $i + 1;
        } else {
          $_activepage = null;
        }

        if ($i > $_limit_less and $i < $_limit_more) {
          if ($_activepage) {
            $_btngroup .= $_activepage;
          } else {
            $_btngroup .= '<a href="/allposts?next='.$i.'">'.$i.'</a>' ."\r\n";
          }
        }
      }

      # 14 defined in Bin_Allposts function pagination_posts
      if ($data['entries'] <= 14 ) {
         $_entries = $data['entries'];
         $_entriesfrom = 1;
         $_entriesto = $data['entries'];
      } else {
         $_entriesto = $data['active_page'] * 14;
         $_entries = $data['entries'];
         $_entriesfrom = $_entriesto - 13;
      }

      if ($_entriesto > $_entries) {
         $_entriesto = $_entries;
      }

      if ($_next_less < 1) {
        echo '<span class="prev">« меньше</span>';
      }
      else {
        echo '<a href="/allposts?next='.$_next_less.'" class="next">« меньше</a>';
      }

      echo $_btngroup;

      if ($_next_more <= $data['pages']) {
        echo '<a href="/allposts?next='.$_next_more.'" class="next">больше »</a>';
      }
      else {
        echo '<span class="prev">больше »</span>';
      }
      echo '</div><small class="smallshelve">Посты&nbsp;' . $_entriesfrom . '&nbsp;и&nbsp;' . $_entriesto . ',&nbsp;из&nbsp;' . $_entries . '</small>';
    }
  ?>
</article>
<footer>
<div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
