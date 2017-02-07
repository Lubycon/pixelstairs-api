<?php $release = isset($release) ? $release : null; ?>
<?php $keep = isset($keep) ? $keep : null; ?>
<?php $local = isset($local) ? $local : null; ?>
<?php $global = isset($global) ? $global : null; ?>
<?php $dir = isset($dir) ? $dir : null; ?>
<?php $HOSTNAME = isset($HOSTNAME) ? $HOSTNAME : null; ?>
<?php $shared_item = isset($shared_item) ? $shared_item : null; ?>
<?php $required_dirs = isset($required_dirs) ? $required_dirs : null; ?>
<?php $distname = isset($distname) ? $distname : null; ?>
<?php $release_dir = isset($release_dir) ? $release_dir : null; ?>
<?php $shared_dir = isset($shared_dir) ? $shared_dir : null; ?>
<?php $project_root = isset($project_root) ? $project_root : null; ?>
<?php $base_dir = isset($base_dir) ? $base_dir : null; ?>
<?php $remote = isset($remote) ? $remote : null; ?>
<?php $username = isset($username) ? $username : null; ?>
##--------------------------------------------------------------------------
# List of tasks, that you can run...
# e.g. envoy run hello
#--------------------------------------------------------------------------
#
# hello     Check ssh connection
# deploy    Publish new release
# list      Show list of releases
# checkout  Checkout to the given release (must provide --release=/path/to/release)
# prune     Purge old releases (must provide --keep=n, where n is a number)
#
#--------------------------------------------------------------------------
# Note that the server shoulbe be accessible through ssh with 'username' account
# $ ssh username@hostname
#--------------------------------------------------------------------------
##

@servers([
  'pro' => 'aws-mitty-provision-test',
  'dev' => 'aws-mitty-devs-test'
])


<?php
  $username = 'deployer';                       // username at the server
  $remote = 'https://bboyzepot:a59846207@bitbucket.org/waltermitty/mitty-web-app-api.git';
  // $remote = 'git@github.com:appkr/envoy.git';   // github repository to clone
  $base_dir = "/home/{$username}/www";          // document that holds projects
  $project_root = "{$base_dir}/mitty/api";       // project root
  $shared_dir = "{$base_dir}/shared";           // directory that will house shared dir/files
  $release_dir = "{$base_dir}/releases";        // release directory
  $distname = 'release_' . date('YmdHis');      // release name

  // ------------------------------------------------------------------
  // Leave the following as it is, if you don't know what they are for.
  // ------------------------------------------------------------------

  $required_dirs = [
    $shared_dir,
    $release_dir,
  ];

  $shared_item = [
    "{$shared_dir}/.env" => "{$release_dir}/{$distname}/.env",
    "{$shared_dir}/storage" => "{$release_dir}/{$distname}/storage",
    "{$shared_dir}/cache" => "{$release_dir}/{$distname}/bootstrap/cache",
  ];
?>


<?php $__container->startTask('hello']); ?>
  HOSTNAME=$(hostname);
  echo "Hello Envoy! Responding from $HOSTNAME";
<?php $__container->endTask(); ?>


<?php $__container->startTask('deploy', ['on' => ['pro']]); ?>
  <?php /*Create directories if not exists*/ ?>
  <?php foreach ($required_dirs as $dir): ?>
    [ ! -d <?php echo $dir; ?> ] && mkdir -p <?php echo $dir; ?>;
  <?php endforeach; ?>

  <?php /*Download book keeping officer*/ ?>
  if [ ! -f <?php echo $base_dir; ?>/officer.php ]; then
    wget https://raw.githubusercontent.com/appkr/envoy/master/scripts/officer.php -O <?php echo $base_dir; ?>/officer.php;
  fi;

  <?php /*Clone code from git*/ ?>
  cd <?php echo $release_dir; ?> && git clone -b master <?php echo $remote; ?> <?php echo $distname; ?>;

  [ ! -f <?php echo $shared_dir; ?>/.env ] && \
    [ -f <?php echo $release_dir; ?>/<?php echo $distname; ?>/.env.example ] && \
    cp <?php echo $release_dir; ?>/<?php echo $distname; ?>/.env.example <?php echo $shared_dir; ?>/.env;
  [ ! -d <?php echo $shared_dir; ?>/storage ] && \
    [ -d <?php echo $release_dir; ?>/<?php echo $distname; ?>/storage ] && \
    cp -R <?php echo $release_dir; ?>/<?php echo $distname; ?>/storage <?php echo $shared_dir; ?>;
  [ ! -d <?php echo $shared_dir; ?>/cache ] && \
    [ -d <?php echo $release_dir; ?>/<?php echo $distname; ?>/bootstrap/cache ] && \
    cp -R <?php echo $release_dir; ?>/<?php echo $distname; ?>/bootstrap/cache <?php echo $shared_dir; ?>;

  <?php /*Symlink shared directory to current release.*/ ?>
  <?php /*e.g. storage, .env, user uploaded file storage, ...*/ ?>
  <?php foreach($shared_item as $global => $local): ?>
    [ -f <?php echo $local; ?> ] && rm <?php echo $local; ?>;
    [ -d <?php echo $local; ?> ] && rm -rf <?php echo $local; ?>;
    [ -f <?php echo $global; ?> ] && ln -nfs <?php echo $global; ?> <?php echo $local; ?>;
    [ -d <?php echo $global; ?> ] && ln -nfs <?php echo $global; ?> <?php echo $local; ?>;
  <?php endforeach; ?>

  <?php /*Run composer install*/ ?>
  cd <?php echo $release_dir; ?>/<?php echo $distname; ?> && \
    [ -f ./composer.json ] && \
    composer install --prefer-dist --no-scripts --no-dev;

  <?php /*Any additional command here*/ ?>
  <?php /*e.g. php artisan clear-compiled;*/ ?>

  <?php /*Symlink current release to service directory.*/ ?>
  ln -nfs <?php echo $release_dir; ?>/<?php echo $distname; ?> <?php echo $project_root; ?>;

  <?php /*Set permission and change owner*/ ?>
  [ -d <?php echo $shared_dir; ?>/storage ] && \
    chmod -R 775 <?php echo $shared_dir; ?>/storage;
  [ -d <?php echo $shared_dir; ?>/cache ] && \
    chmod -R 775 <?php echo $shared_dir; ?>/cache;
  chgrp -h -R www-data <?php echo $release_dir; ?>/<?php echo $distname; ?>;

  <?php /*Book keeping*/ ?>
  php <?php echo $base_dir; ?>/officer.php deploy <?php echo $release_dir; ?>/<?php echo $distname; ?>;

  <?php /*Restart web server.*/ ?>
  sudo service nginx restart;
  sudo service php7.0-fpm restart;
<?php $__container->endTask(); ?>


<?php $__container->startTask('prune', ['on' => 'pro']); ?>
  if [ ! -f <?php echo $base_dir; ?>/officer.php ]; then
    echo '"officer.php" script not found.';
    echo '\$ envoy run hire_officer';
    exit 1;
  fi;

  <?php if (isset($keep) and $keep > 0): ?>
    php <?php echo $base_dir; ?>/officer.php prune <?php echo $keep; ?>;
  <?php else: ?>
    echo 'Must provide --keep=n, where n is a number.';
  <?php endif; ?>
<?php $__container->endTask(); ?>


<?php $__container->startTask('hire_officer', ['on' => 'pro']); ?>
  <?php /*Download "officer.php" to the server*/ ?>
  wget https://raw.githubusercontent.com/appkr/envoy/master/scripts/officer.php -O <?php echo $base_dir; ?>/officer.php;
  echo '"officer.php" is ready! Ready to roll master!';
<?php $__container->endTask(); ?>


<?php $__container->startTask('list', ['on' => 'pro']); ?>
  <?php /*Show the list of release*/ ?>
  if [ ! -f <?php echo $base_dir; ?>/officer.php ]; then
    echo '"officer.php" script not found.';
    echo '\$ envoy run hire_officer';
    exit 1;
  fi;

  php <?php echo $base_dir; ?>/officer.php list;
<?php $__container->endTask(); ?>


<?php $__container->startTask('checkout', ['on' => 'pro']); ?>
  <?php /*checkout to the given release path*/ ?>
  if [ ! -f <?php echo $base_dir; ?>/officer.php ]; then
    echo '"officer.php" script not found.';
    echo '\$ envoy run hire_officer';
    exit 1;
  fi;

  <?php if (isset($release)): ?>
    cd <?php echo $release; ?>;

    <?php /*Symlink shared directory to the given release.*/ ?>
    <?php foreach($shared_item as $global => $local): ?>
      [ -f <?php echo $local; ?> ] && rm <?php echo $local; ?>;
      [ -d <?php echo $local; ?> ] && rm -rf <?php echo $local; ?>;
      ln -nfs <?php echo $global; ?> <?php echo $local; ?>;
    <?php endforeach; ?>

    <?php /*Symlink the given release to service directory.*/ ?>
    ln -nfs <?php echo $release; ?> <?php echo $project_root; ?>;

    <?php /*Book keeping*/ ?>
    php <?php echo $base_dir; ?>/officer.php checkout <?php echo $release; ?>;
    chgrp -h -R www-data <?php echo $release_dir; ?>/<?php echo $distname; ?>;

    <?php /*Restart web server.*/ ?>
    sudo service nginx restart;
    sudo service php7.0-fpm restart;
  <?php else: ?>
    echo 'Must provide --release=/full/path/to/release.';
  <?php endif; ?>
<?php $__container->endTask(); ?>