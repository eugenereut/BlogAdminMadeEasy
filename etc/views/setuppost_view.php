<script>
	function bookcase_chechbox(idbc) {
    var responseData = "",
		value_checkbox = document.getElementById(idbc).checked,
		xhttp = new XMLHttpRequest();

    if(value_checkbox)
    {
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200)
        {
            var responseData = eval(this.responseText);
            var inner_var = document.getElementById("attachedbookcases").innerHTML;
             inner_var += responseData[0];
             document.getElementById("attachedbookcases").innerHTML = inner_var;
            //console.log(responseData[0] + " / " + responseData[1] + " / " + value_checkbox);
        }
      };
       sendpost(xhttp, "addposttobcs=", idbc);
    }
    else
    {
      // here goes remove Request
			xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200)
        {
            var responseData = eval(this.responseText);
						var parent = document.getElementById("attachedbookcases");
			      var child = document.getElementById(responseData[0]);
			      parent.removeChild(child);
            //console.log(responseData[0] + " / " + responseData[1] + " / " + value_checkbox);
        }
      };
       sendpost(xhttp, "deletepostfrombcs=", idbc);
    }
	}

	function shelve_chechbox(shlvid) {
    var responseData = "",
		value_checkbox = document.getElementById(shlvid).checked,
		xhttp = new XMLHttpRequest();

    if(value_checkbox)
    {
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200)
        {
            var responseData = eval(this.responseText);
            var inner_var = document.getElementById("attachedbookcases").innerHTML;
             inner_var += responseData[0];
             document.getElementById("attachedbookcases").innerHTML = inner_var;
            //console.log(responseData[0] + " / " + responseData[1] + " / " + value_checkbox);
        }
      };
       sendpost(xhttp, "addposttoshelve=", shlvid);
    }
    else
    {
      // here goes remove Request
			xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200)
        {
            var responseData = eval(this.responseText);
						var parent = document.getElementById("attachedbookcases");
			      var child = document.getElementById(responseData[0]);
			      parent.removeChild(child);
            //console.log(responseData[0] + " / " + responseData[1] + " / " + value_checkbox);
        }
      };
       sendpost(xhttp, "deletepostfromshelve=", shlvid);
    }
	}

	function sendpost(xhttp, post_request, idbc) {
		xhttp.open("POST", "setuppost", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhttp.send(post_request + idbc);
	}
</script>
<article>
<header>
<h2>Add Post into Bookcases and Shelves. Edit or delete post.</h2>
<?php
  if (!empty($data['houston'])) {
    echo '<p style="color: red">'.$data['houston'].'</p>';
  } ?>
</header>
<section>
<header>
<h1><?php if (!empty($data['PostName'])) {echo $data['PostName'];} ?></h1>
<time><?php if (!empty($data['PostDate'])) {echo $data['PostDate'];} ?></time>
<br><br>
<div id="attachedbookcases"><?php if (!empty($data['PostBcSh'])) {echo $data['PostBcSh'];} ?></div>
<br><br>
<div>
	<p><a href="/post?idpt='. <?php if (!empty($data['PostDate'])) {echo $data['PostDate'];} ?> . '" class="readmore">Edit</a></p>
	<p>&mdash;&nbsp;or&nbsp;&mdash;</p>
	<p><a href="/post?idpt='. <?php if (!empty($data['PostDate'])) {echo $data['PostDate'];} ?> . '" class="readmore">Delete Post</a></p>
</div>
</header>
<div class="postbody">Bookcases and Shelves<br><br>
  <?php
  if (!empty($data['bookcase'])) {
    foreach ($data['bookcase'] as $value) {
      echo '<section class="third">
      <div>
			<h2><input type="checkbox" id="bcid'.$value['Record'].'" value="" onchange="bookcase_chechbox(this.id)" ' . $value['Checked'] . '>' . $value['NameBookcase'] . '</h2>
      <br>' . $value['AboutBC'] . '</div>
      <div class="listbookcases"><ul class="shelvenav">' . $value['Shelve'] . '</ul></div>
      </section>';
    }
  } ?>
</div>
</section>
</article><br><br>
<footer>
  <div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
