var openDetailsModal;
require([
  'jquery',
  'Magento_Ui/js/modal/modal',
  'AsaanShopping_SearchByLocation/js/datatables',
  'domReady!'
], function($, modal, datatables)
  {
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

    try{
      $("#super-product-table").DataTable({
        "language": {
          "lengthMenu": '<small>PAGE SIZE</small><select>'+
            '<option value="10">10</option>'+
            '<option value="25">25</option>'+
            '<option value="50">50</option>'+
            '<option value="-1">All</option>'+
            '</select>',
          "search": '<small><span class="ic ic-search"></span> FIND THE PRODUCT</small>',
          "searchPlaceholder": "Enter here that you need"
        }
      });

    }catch(e){}
});
