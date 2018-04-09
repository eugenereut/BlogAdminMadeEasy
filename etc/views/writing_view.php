<style type="text/css" media="all">
	@import "/common/vendor/widgEditor/css/widgEditor.css";
</style>
<article>
<header>
<h2>New Post</h2>
</header>
<form action=""  method="post">
<section class="third">
<div><input type="text" name="Dates" class="form-control text-center" id="datefrom" value="<?php echo date('D M d Y');?>" required></div>
<div class="newbookcases">
    <div class="inputwrap">
      <input value="" name="headerpost" placeholder="Header of post" type="text">
    </div>
</div>
</section>
<section>
  <div class="formpostbody widgContainer">
    <fieldset class="newpostarea">
      <textarea id="noise" name="noise" class="widgEditor nothing">
Text
      </textarea>
    </fieldset>
    <fieldset class="submit">
      <input value="Add new post" name="addnewpost" type="submit">
    </fieldset>
</div>
</section>
</form>
</article>
<br><br>
<footer>
  <div>© Авторские права 2010—2018, Священник Яков Кротов</div>
</footer>
<script src="/common/vendor/widgEditor/scripts/widgEditor.js"></script>
