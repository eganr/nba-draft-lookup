<header id="header" class="header" role="header">
    <div class="container">
      <nav class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="sr-only"><?php print t('Toggle navigation'); ?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <?php if ($site_name || $logo): ?>
            <a href="<?php print $front_page; ?>" class="navbar-brand" rel="home" title="<?php print t('Home'); ?>">
              <?php if ($logo): ?>
                <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" id="logo" />
              <?php endif; ?>
              <?php if ($site_name): ?>
                <span class="site-name"><?php print $site_name; ?></span>
              <?php endif; ?>
            </a>
          <?php endif; ?>
        </div> <!-- /.navbar-header -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse">
          <?php if ($main_menu): ?>
            <ul id="main-menu" class="menu nav navbar-nav">
              <?php print render($main_menu); ?>
            </ul>
          <?php endif; ?>
          <?php if ($search_form): ?>
            <?php print $search_form; ?>
          <?php endif; ?>
        </div><!-- /.navbar-collapse -->
      </nav><!-- /.navbar -->
    </div> <!-- /.container -->
</header>

<div id="main-wrapper">
  <div id="main" class="main">
    <div id="content" class="container">
        <?php print render($page['content']); ?>
    </div>
  </div>
</div>

<footer id="footer" class="footer" role="footer">
  <div class="container">
    <small class="pull-right"><a href="#"><?php print t('Back to Top'); ?></a></small>
  </div>
</footer>
