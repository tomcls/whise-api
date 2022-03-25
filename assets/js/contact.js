const lang = "fr-BE";
const officeId = '2045';
let notifyApiUri = "https://whise-api.itcl.io";

function contactController() {
    
    let notify = new Notify({});
    let $contactForms = $('.elementor-form');
   
    $contactForms.on('submit', function(e) {
        e.preventDefault();
        
        let payload = {
            "ContactTitleId": 181,
            "BaseContactTitleId": 0,
            "Name": $('#form-field-lastname').val(),
            "FirstName": $('#form-field-firstname').val(),
            "Address1": "",
            "Address2": "",
            "Number": "",
            "Box": "",
            "Zip": "",
            "City": "",
            "CountryId": 1,
            "PrivateTel": $('#form-field-phone').val(),
            "PrivateEmail": $('#form-field-email').val(),
            "AgreementEmail": true,
            "AgreementSms": true,
            "Comments": "",
            "Message": $('#form-field-firstname').val()+" "+$('#form-field-lastname').val()+" souhaite vous contacter avec le message suivant: "+$("#form-field-message").val(),
            "ContactOriginId": 353,
            "PrivateMobile": $('#form-field-phone').val(),
            "FunnelStatusId": 1,
            "AgreementMailingCampaign": true,
            "OfficeIds": [officeId],
            "EstateIds": [],
            "ContactTypeIds": [4766],
            "BaseContactTypeId": 0,
            "RepresentativeIds": [],
            "SearchCriteria": null,
            "LanguageId": lang
        }
        console.log(payload);
        notify.registerWhiseLead(payload).always(function(e){
            alert("Nous avons bien recu votre message, merci");
        });
    });
}
function Notify(config) {
    let hash = null;
    
    if(config && config.hash){
        hash = config.hash;
    }
    if(config && config.notifyApiUri){
        notifyApiUri = config.notifyApiUri;
    }
    function _registerWhiseLead(obj)
    {
        let deferred = $.Deferred();
        let param = obj;
        let d = JSON.stringify(param);
        let settings = {
            "data":d,
            "content-type":"application/json",
            "async": true,
            "crossDomain": true,
            "url": notifyApiUri+"/contact.php", 
            "method": "POST"
        }
        $.ajax(settings).always(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise();
    }
    return {
        registerWhiseLead: function(o){
            return _registerWhiseLead(o);
        }
    };
}
$(document).ready(function () {
    
    let contact = new contactController();
});