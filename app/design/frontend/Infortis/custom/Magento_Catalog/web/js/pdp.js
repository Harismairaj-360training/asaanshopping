var openDescModal;
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
    }, $('#reviewModal'));

    modal({
      type: 'popup',
      responsive: true,
      innerScroll: false,
      buttons: [],
    }, $('#descriptionModal'));

    openDescModal = function()
    {
      $('#descriptionModal').modal("openModal");
    }
    //$('#descriptionModal').modal("openModal");
});
