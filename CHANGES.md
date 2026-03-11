# Issue executing command  php artisan migrate --seed
There was some models, relations missing on the application causing the command above to fail and in deployment error in production

## Contact Model was missing 
Since application scope is to send campaings to list of contacts, is not possible to send when the model is missing cause application error, however the issue ocurred when running the seeder class, executing the factory related to this model, had to include the HasFactory trait

## CampaingSend Model was missing 
Without this model is not possible to insure that the campaing was send to a specific Contact, this model will track the success of mail Campaing delivering, it will make the CampaignService to fail

## CampaingSend Relations 
Had to create relations for Contact and Campaing for  the CampaingSend, is not possible to record and CampaingSend without Contact and Campaing information, will cause the SendCampaignEmail to fail, this was belongTo relation for both

## ContactList contacts Relations Missing 
Ever Campaing has a Contact List, the contact relation was not defined on the model ContactList, Without contact Relation is not possible to reach an audience, and app context will cause and the CampaignService to fail


# API Contacts
The Contacts api was create using closures, i could use Controllers, but i think should be fine for the test..
I create groups of routes for than endpoint /api/contacts and FormRequests

## GET /api/contacts 
Get All paginate Contacts using the  paginate method available on Eloquent API and sorted by name

## POST /api/contacts
Create a POST Route, including a FormRequest called ContactPostRequest to validate the request
Added override to ContactPostRequest to return json response
Created and Enumerator for the status called ContactStatus for validation and also to cast the enum field in Contact Model

## POST /api/contacts/{id}/unsubscribe
Created a POST Route with {id} parameter, then retrieved instance of Contact model. Create a method unsubscribe, to store the change on the Database.
