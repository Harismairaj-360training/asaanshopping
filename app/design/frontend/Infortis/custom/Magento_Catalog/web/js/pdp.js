var openDetailsModal;
require([
  'jquery',
  'Magento_Ui/js/modal/modal',
  'domReady!'
  ], function($, modal)
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
});
