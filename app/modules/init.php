<?php
require_once "exceptions/index.php";

/**
 * @param string $module - Das Module was importiert werden soll.
 * @throws ModuleNotFoundException
 * @since 1.0.0
 */
function require_mod(string $module): void
{
   if (!is_dir(APP_MODULES_PATH . "/$module")) {
      if (is_file(APP_MODULES_PATH . "/$module.php")) {
         require_once APP_MODULES_PATH . "/$module.php";
         return;
      }

      throw new ModuleNotFoundException($module);
   }

   require_once "$module/index.php";
}
