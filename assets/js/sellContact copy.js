//<script src="https://jam.itcl.io/wp-content/uploads/jquery-3.6.0.min.js"></script>
//<script>
/***
 * Notify object and its methods
 */
 
const applicationId = 'jam';
const apiHash = "a4Bbb4hZek2vGFTIcX0W19bZsOg0Gh4As175q857DAA";
const lang = "fr-BE";
const companyMail = "tom@itcl.io";
const labelMessageSent = "message bien envoyé";
const labelSelectDate = new Date();
const labelEnterAtLeast = "Enter at least";
const labelSelectTimeRange = "Select a time rang";
const labelUnknownError = "Unknown";
const whiseId = "99d8bcb535904f41841c";
const mailFrom = "tom@itcl.io";
const purpose = 'FOR_SALE';
const officeId = '2045';
let notifyApiUri = "http://whise-api.itcl.io";

function contactController() {
    
    let notify = new Notify({
        "hash": apiHash,
        "mailTo": companyMail,
        "labelMessageSent": labelMessageSent,
        "labelSelectDate": labelSelectDate,
        "labelSelectTimeRange": labelSelectTimeRange,
        "labelEnterAtLeast": labelEnterAtLeast,
        "labelUnknownError": labelUnknownError
    });
    let $contactForms = $('.elementor-form');

    $contactForms.on('submit', function(e) {
        e.preventDefault();
        
        let message =$('#form-field-firstname').val()+" "+$('#form-field-lastname').val()+ " souhaite vendre sont bien "+$('#form-field-type').val();
        
        message = message +" à l'adresse: "+ $('#form-field-address').val()+", " + $('#form-field-zip').val()+"  \n Merci de lui soumettre une estimation!";

        let payload = {
            "ContactTitleId": 181,
            "BaseContactTitleId": 0,
            "Name": $('#form-field-lastname').val(),
            "FirstName": $('#form-field-firstname').val(),
            "Address1": $('#form-field-address').val(),
            "Address2": "",
            "Number": "",
            "Box": "",
            "Zip": $('#form-field-zip').val(),
            "City": "",
            "CountryId": 1,
            "PrivateTel": $('#form-field-phone').val(),
            "PrivateEmail": $('#form-field-email').val(),
            "AgreementEmail": true,
            "AgreementSms": true,
            "Comments": "",
            "Message": message,
            "ContactOriginId": 353,
            "PrivateMobile": $('#form-field-phone').val(),
            "FunnelStatusId": 1,
            "AgreementMailingCampaign": true,
            "OfficeIds": [officeId],
            "EstateIds": [],
            "ContactTypeIds": [4766],
            "BaseContactTypeId": 0,
            "RepresentativeIds": [],
            "SearchCriteria": [],
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
            "url": notifyApiUri+"/contact.php", //notifyApiUri+
            "method": "POST"
        }
        //deferred.resolve(settings);
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
    $.getJSON( "https://api.whise.eu/reference?item=category&lang=fr-BE", function(data) {})
        .done(function(data) {
          console.log( " success",data );
          $("#form-field-type").empty();
          $.each(data, function(key,value) {
              $("#form-field-type").append($("<option></option>")
                 .attr("value", value.id).text(value.name));
          });
    });
    new contactController();
});
//</script>