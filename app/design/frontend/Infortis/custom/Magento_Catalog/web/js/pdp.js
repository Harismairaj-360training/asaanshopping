var openDetailsModal,filterAssociateProducts;
require([
  'jquery',
  'Magento_Ui/js/modal/modal',
  'AsaanShopping_SearchByLocation/js/datatables',
  'domReady!'
], function($, modal, datatables)
  {
    try{
      var productTable = $("#super-product-table").DataTable({
        "language": {
          "lengthMenu": '<small>PAGE SIZE</small><select>'+
            '<option value="10">10</option>'+
            '<option value="25">25</option>'+
            '<option value="50">50</option>'+
            '<option value="-1">All</option>'+
            '</select>',
          "search": '<small><span class="ic ic-search"></span> FIND THE PRODUCT</small>',
          "searchPlaceholder": "Enter product name here.."
        }
      });
    }catch(e){}

    modal({
      type: 'popup',
      responsive: true,
      innerScroll: false,
      buttons: [],
    }, $('#detailsModal'));

    openDetailsModal = function()
    {
      $('#detailsModal').modal("openModal");
    }
    filterAssociateProducts = function(event)
    {
      if(typeof productTable != "undefined")
      {
        var value = event.currentTarget.value;
        if(value == "*")
        {
          productTable.columns(0).search(" ").draw();
        }
        else
        {
          var regex = '\\b'+value+'\\b';
          if(value.indexOf(' ') >= 0)
          {
            productTable.columns(0).search(regex,true,false).draw();
          }
          else
          {
            productTable.columns(0).search(value).draw();
          }
        }
      }
    }
});
