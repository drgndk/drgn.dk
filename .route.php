<?php

// Prepares the Request
require_once "app/prepare.php";

// MIND: `public Bucket::grant_access(): void` -> `private Bucket::update_bucket(): bool`
// Abfrage als eigene Method in `Bucket` erstellen.
require_mod("bucket");
if (!Bucket::update_bucket()) {
   new RateLimitExceededException();
}

// TODO: File System
$types = [
   "css" => "text/css",
   "svg" => "image/svg+xml",
   "png" => "image/png",
   "js" => "text/javascript",
   "html" => "text/html",
   "php" => "text/html",
   "json" => "application/json",
   "xml" => "application/xml",
];

// TODO: Routing System
function redirect(string $path, string $type = "html")
{
   global $types;

   header("Content-Length: " . filesize($path));
   header("Content-Type: " . ($types[$type] ?? "text/html"));
   header("Last-Modified: " . gmdate("D, d M Y H:i:s T", filemtime($path)));

   include "$path";
   exit();
}

// TODO: String
// `String` sollte dann ::sanitize(string), sowie ::pathify(string) haben.
require_mod("path");
$requested_path = new Path(htmlentities(substr($_SERVER["REQUEST_URI"] ?? "", 1)) ?: "home");

// Create Public Path from Requested by prefixing the "public" directory.
$public_path = Path::from("public/$requested_path")->default_extension("php");

// If Public Path does not exist, fallback to Requested Path, if its a resource, otherwise show 404.
if (!$public_path->is_file()) {
   if ($requested_path->dirname()->starts_with("resources") && $requested_path->is_file()) {
      redirect($requested_path, $requested_path->extension("html"));
   }

   // TODO: Routing System
   error_page("404");
}

// MIND: `->extension(string $fallback): string` extension sollte ein fallback, als Argument, haben.
$return_type = $public_path->extension("html");

// MIND: Routing System
// Routing System sollte automatisch Doctype und Metadaten setzen, wenn der Content-Type "text/html" ist.
if (($types[$return_type] ?? false) === "text/html") {
   $metadata = [["charset" => "UTF-8"], ["name" => "viewport", "content" => "width=device-width, initial-scale=1.0"], ["name" => "description", "content" => "Fun Site maintained by @drgndk"], ["name" => "author", "content" => "drgndk"], ["name" => "theme-color", "content" => "#0F0F0F"], ["name" => "robots", "content" => "index, follow"], ["name" => "keywords", "content" => "drgndk, dragonduck"], ["name" => "twitter:title", "property" => "og:title", "content" => "drgndk - web developer & designer"], ["name" => "twitter:description", "property" => "og:description", "content" => "Fun Site maintained by @drgndk"], ["property" => "og:type", "content" => "website"], ["property" => "og:url", "content" => "https://drgn.dk"], ["name" => "twitter:image", "property" => "og:image", "content" => "/assets/og-image.png"]];

   echo "<!DOCTYPE html>\n";
   foreach ($metadata as $meta) {
      $metaTag = "";
      foreach ($meta as $key => $value) {
         $metaTag .= "$key='$value' ";
      }
      $metaTag = trim($metaTag);
      echo "<meta $metaTag>\n";
   }
}

// TODO: Routing System
redirect($public_path, $return_type);
