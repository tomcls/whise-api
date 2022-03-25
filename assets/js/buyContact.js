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
        
        let purposeId = 1;
        let purposeString = "acheter";
        let message =$('#form-field-firstname').val()+" "+$('#form-field-lastname').val()+ " souhaite ";
        
        if($('#form-field-purpose-1').prop('checked')){
            purposeId = 2;
            purposeString = "louer";
        }
        message = message + purposeString+" dans la commune de "+ $('#form-field-municipality').text();

        let evaluation = false;
       /* if($('#form-field-evaluation-1').prop('checked')){
            evaluation = true;
            message = message + " Il souhaite aussi recevoir une estimation gratuite";
        }*/
        message =  message + "\n Voici son message:\n "+$("#form-field-message").val();
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
            "SearchCriteria": [
                {
                    "PurposeId": purposeId,
                    "CategoryId": parseInt($('#form-field-type').val(),10),
                    "SubcategoryId": 0,
                    "PriceMax":parseInt($('#form-field-pricemax').val(),10),
                    "PriceMin": parseInt($('#form-field-pricemin').val(),10),
                    "GardenAreaMax": 0,
                    "GardenAreaMin": 0,
                    "GroundAreaMax": 0,
                    "GroundAreaMin": 0,
                    "AreaMax": 0,
                    "AreaMin": 0,
                    "CountryId": 1,
                    "MinRooms": 0,
                    "Garage": false,
                    "Garden": false,
                    "AutomaticMatching": true,
                    "Fronts": 0,
                    "Terrace": false,
                    "Furnished": false,
                    "InvestmentEstate": false,
                    "Parking": false,
                    "State": 0,
                    "Bathrooms": 0,
                    "Floor": false,
                    "Pool": false,
                    "RegionIds": [],
                    "ZipCodes": [parseInt($('#form-field-municipality').val(),10)]
                }
            ],
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
    $.getJSON( notifyApiUri+"/locations.php", function(data) {})
        .done(function(data) {
          console.log( " success",data );
          $("#form-field-municipality").empty();
          $.each(data, function(key,value) {
              $("#form-field-municipality").append($("<option></option>")
                 .attr("value", value.zip).text(value.fr));
          });
    });
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