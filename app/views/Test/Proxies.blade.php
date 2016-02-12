<html>
<body>
	<?php if (Input::get('proxies')): ?>
		<h3 style="color:green;">Proxies Updated!</h3>	
	<?php endif ?>
  <form method="post" action="/test/proxies">
    <textarea rows="40" cols="100" name="proxies"><?php echo $proxies; ?></textarea>
    <br/>
    <button type="submit">Save</button>
  </form>
</body>
</html>