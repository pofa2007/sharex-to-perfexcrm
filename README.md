# sharex-to-perfexcrm

accept uploads form sharex and place them in media/public/sharex folder

## to install
### server side:
1. update security tokens
2. just copy sharex.php to perfexcrm root folder 

### client side:
1. create a file named : sharex.sxcu 
2. put the contents: 

<code>
  {
  "Name": "to perfexcrm",
  "DestinationType": "ImageUploader, TextUploader, FileUploader",
  "RequestType": "POST",
  "RequestURL": "https://perfexcrm_url_here/sharex.php",
  "FileFormName": "sharex",
  "Arguments": {
    "secret": "putyourtoken_abcd"
  },
  "ResponseType": "Text",
  "URL": "https://perfexcrm_url_here/media/public/sharex/$json:url$"
 }
</code>
    
    
    
    
2.1 change the urls to Perfexcrm url  
2.2 update secret to match tokens on server side  
2.3 double click to import settings to sharex  
