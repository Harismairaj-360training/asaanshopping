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

    var isDescAvailable = (typeof jQuery('#descriptionModal').html() != "undefined");
    if(isDescAvailable)
    {
      modal({
        type: 'popup',
        responsive: true,
        innerScroll: false,
        buttons: [],
      }, $('#descriptionModal'));
    }

    openDescModal = function()
    {
      if(isDescAvailable)
      {
        $('#descriptionModal').modal("openModal");
      }
    }
});
