<script>
function getpostin_modalwindow(name, id) {
  var responseData = "", xhttp = new XMLHttpRequest();

  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200)
    {
          var responseData = eval(this.responseText);
           document.getElementById("wrapped_html").innerHTML = responseData[0];
          //console.log(responseData[0]);
    }
  };

  sendpost(xhttp, name, id);
}

function sendpost(xhttp, name, id) {
  xhttp.open("POST", "shelve", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("iDShelve=" + name + "&iDPage=" + id);
}
</script>
<article>
<header>
<?php
if (!empty($data['ShelvesMenu'])) {
  echo '<ul class="themas">';
  foreach ($data['ShelvesMenu'] as $value) {
    echo $value['SrtSh'];
  }
  echo '<li class="alltexts-btn"> | <a id="myBtn">all posts</a></li></ul>';
}

if (!empty($data['Aboutbookcase'])) {echo '<span class="aboutbookcase"><em>' . $data['Aboutbookcase'] . '</em></span>';}
?>
</header>
<?php
if (!empty($data['Posts'])) {
  foreach ($data['Posts'] as $value) {
    echo '<section class="post">
    <header>
    <a href="/post?idpt='.$value['PostID'].'" class="title parastyle">' . $value['PostName'] . '</a>
    <time>' . $value['PostDate'] . '</time>
    <br><br>
    <div id="attachedbookcases">' . $value['PostBcSh'] . '</div>
    </header>
    <div class="postbody">' . $value['PostBody'] . '<p><a href="/post?idpt='.$value['PostID'].'" class="readmore">read more…</a></p>
    </div>
    </section>';
  }
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
        $_btngroup .= '<a href="/shelve?idsh='.$data['iDsh'].'&next='.$i.'">'.$i.'</a>' ."\r\n";
      }
    }
  }

  # 7 defined in Bin_Shelve function pagination_posts
  if ($data['entries'] <= 7 ) {
     $_entries = $data['entries'];
     $_entriesfrom = 1;
     $_entriesto = $data['entries'];
  } else {
     $_entriesto = $data['active_page'] * 7;
     $_entries = $data['entries'];
     $_entriesfrom = $_entriesto - 6;
  }

  if ($_entriesto > $_entries) {
     $_entriesto = $_entries;
  }

  if ($_next_less < 1) {
    echo '<span class="prev">« here</span>';
  }
  else {
    echo '<a href="/shelve?idsh='.$data['iDsh'].'&next='.$_next_less.'" class="next">« here</a>';
  }

  echo $_btngroup;

  if ($_next_more <= $data['pages']) {
    echo '<a href="/shelve?idsh='.$data['iDsh'].'&next='.$_next_more.'" class="next">there »</a>';
  }
  else {
    echo '<span class="prev">there »</span>';
  }
  echo '</div><small class="smallshelve">Posts&nbsp;' . $_entriesfrom . '&nbsp;and&nbsp;' . $_entriesto . ',&nbsp;from&nbsp;' . $_entries . '</small>';
}
?>
</article>
<!-- services Modal window -->
<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <span id="wrapped_html">
      <?php if (!empty($data['wrapped_html'])){echo $data['wrapped_html'];}?>
    </span>
  </div>
</div>
<script>
// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
if (event.target == modal) {
    modal.style.display = "none";
}
}
</script>
<footer>
<div>© Copyright <?php echo date('Y');?>, Blog Admin Made Easy</div>
</footer>
