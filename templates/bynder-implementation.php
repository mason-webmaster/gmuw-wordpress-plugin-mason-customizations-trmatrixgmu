<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>George Mason Bynder Compact View</title>
  </head>
  <body>
    <div id="importedAssets" style="text-align:center;"></div>
    <p style="text-align:center; font-size:2em;">
      <a style="color:#005239;" href="/browse/">Return to Bynder browser</a>
    </p>
    <p style="text-align:center; font-size:2em;">
      <a style="color:#005239;" href="/">Return to homepage</a>
    </p>
    <script src="https://ucv.bynder.com/5.0.5/modules/compactview/bynder-compactview-3-latest.js"></script>
    <script>
      BynderCompactView.open({
        language: "en_US",
        theme: {
          colorButtonPrimary: "#3380FF"
        },
        authentication : {
          getAccessToken: () => 'cf710e6cd64844a7c3b7d0f0f33f19b9b602831a5523810f4fb6e94ab19c3261',
          hideLogout: true
        },
       portal: {
          url: "gmu.bynder.com"
        },
        mode: "SingleSelectFile",
        onSuccess: function(assets, additionalInfo) {
          var importedAssetsContainer = document.getElementById(
            "importedAssets"
          );
          importedAssetsContainer.innerHTML = "";
          var asset = assets[0];
          console.log(asset, additionalInfo);
          switch (asset.type) {
            case "IMAGE":
              importedAssetsContainer.innerHTML +=
                "<p>Right-click on the image below to download it to your computer.</p>";
              importedAssetsContainer.innerHTML +=
                "<img src=" + additionalInfo.selectedFile.url + " />";
              importedAssetsContainer.innerHTML +=
                "<p>For access to unwatermarked images, please contact <a href='mailto:creative@gmu.edu'>creative@gmu.edu</a></p>";
              return;
            case "AUDIO":
            case "DOCUMENT":
              importedAssetsContainer.innerHTML +=
                "<img src=" + asset.files.webImage.url + " />";
              return;
            case "VIDEO":
              importedAssetsContainer.innerHTML +=
                '<video width="640" height="480" controls>' +
                '<source src="' +
                asset.previewUrls[0] +
                '" type="video/webm">' +
                "</video>";
              return;
            default:
              return;
          }
        }
      });
    </script>
  </body>
</html>
