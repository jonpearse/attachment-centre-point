(function($)
{
    function AttachmentCentrePoint()
    {
        var elTrigger = this;
        var sFieldId  = (elTrigger.hasAttribute('data-id')) ? elTrigger.getAttribute('data-id') : 'thisfieldwillneverexist';
        var elField   = document.querySelector('#'+sFieldId);
        var elFocus;
        var elXSlider;
        var elYSlider;
        var elOverlay;
        var oTo;

        return init();

        function init()
        {
            // 0. if we don’t have a field, bail
            if (elField === null)
                return false;

            // 1. find us an image and wrap it
            buildImageDom();

            // 2. munge the input DOM and hide the trigger
            if (!createInputDom())
                return false;
            $(elTrigger).parents('tr').eq(0).remove();

            // 3. bind events
            elXSlider.addEventListener('change', fnHandleSliderChange);
            elYSlider.addEventListener('change', fnHandleSliderChange);
            elOverlay.addEventListener('click',  fnHandleOverlayClick);

            // 4. trigger
            fnHandleSliderChange();
        }

        function buildImageDom()
        {
            // 0. get an ID
            var iId = sFieldId.replace(/[\D]/g, '', sFieldId);

            // 1. get the image
            var elImage = document.querySelector('#thumbnail-head-'+iId+' img, .attachment-details[data-id="'+iId+'"] .details-image');

            // 2. if there’s a wrapper, just return stuff
            if (elImage.parentNode.className.indexOf('attachment-centre-point__wrapper') !== -1)
            {
                elFocus   = elImage.parentNode.querySelector('.attachment-centre-point__focus');
                elOverlay = elImage.parentNode.querySelector('.attachment-centre-point__overlay');
                return;
            }

            // 3. otherwise, wrap
            $(elImage).wrap('<div class="attachment-centre-point__wrapper"/>');
            var elWrap = elImage.parentNode;

            // 4. create a focus point
            elFocus = document.createElement('span');
            elFocus.className = 'attachment-centre-point__focus';
            elWrap.appendChild(elFocus);

            // 5. create a click overlay
            elOverlay = document.createElement('div');
            elOverlay.className = 'attachment-centre-point__overlay';
            elWrap.appendChild(elOverlay);
        }

        function createInputDom()
        {
            // 0. get a value
            var aM;
            if ((aM = elField.value.match(/(\d+)% (\d+)%/)) === false)
                return false;

            // 1. hide the existing field
            elField.setAttribute('type', 'hidden');

            // 2. create our new sliders
            elXSlider = $('<input>').attr({
                'type':     'number',
                'id':       sFieldId+'__x',
                'class':    'attachment-centre-point__input',
                'min':      0,
                'max':      100,
                'step':     1,
                'required': true
            })[0];
            elYSlider = elXSlider.cloneNode();
            elYSlider.setAttribute('id', sFieldId+'__y');

            // 3. create a fieldset
            var oFs = $('<fieldset class="attachment-centre-point__fieldset"/>').appendTo(elField.parentNode);

            // 4. X slider
            var oField = $('<div class="attachment-centre-point__field">').appendTo(oFs);
            oField.append('<label class="attachment-centre-point__label" for="'+sFieldId+'__x">Left</label>').append(elXSlider);
            oField.append('<label class="attachment-centre-point__label -after" for="'+sFieldId+'__x">%</label>');

            // 5. Y slider
            oField = $('<div class="attachment-centre-point__field">').appendTo(oFs);
            oField.append('<label class="attachment-centre-point__label" for="'+sFieldId+'__y">Top</label>').append(elYSlider);
            oField.append('<label class="attachment-centre-point__label -after" for="'+sFieldId+'__y">%</label>');

            // 6. values
            elXSlider.value = aM[1];
            elYSlider.value = aM[2];

            // 7. classes
            elField.parentNode.parentNode.querySelector('th.label label').className += ' attachment-centre-point__outer-label';
            return true;
        }

        function fnHandleSliderChange(ev)
        {
            // 1. read back values
            var iX = parseInt(elXSlider.value);
            var iY = parseInt(elYSlider.value);

            // 2. sanity-check
            if ((iX !== iX) || (iY !== iY))
                return true;

            // 3. set values
            setValue(iX, iY);

            // 4. stop things percolating
            if (ev !== undefined)
            {
                ev.preventDefault();
                ev.stopPropagation();
            }

            return true;
        }

        function fnHandleOverlayClick(ev)
        {
            // 1. get X/Y pixel coordinates
            var iPosX = ev.offsetX;
            var iPosY = ev.offsetY;

            // 2. convert to percentages
            iPosX = positionToPercent(iPosX, elOverlay.scrollWidth);
            iPosY = positionToPercent(iPosY, elOverlay.scrollHeight);

            // 3. move the pointer
            setValue(iPosX, iPosY);

            // 4. update the sliders
            elXSlider.value = iPosX;
            elYSlider.value = iPosY;

            return false;
        }

        function positionToPercent(iPosition, iSize)
        {
            var iPercentage = Math.round((iPosition / iSize) * 100);

            return Math.max(Math.min(iPercentage, 100), 0);
        }

        function setValue(iX, iY)
        {
            // 1. move the pointer
            elFocus.style.top  = iY+'%';
            elFocus.style.left = iX+'%';

            // 2. update field
            elField.value = iX+'% '+iY+'%';

            // 3. clear the timeout and reset it
            clearTimeout(oTo);
            oTo = setTimeout(function()
            {
                $(elField).trigger('change');
            }, 1000);
        }
    }

    /**
     * Wakeup handler: binds to the ‘open’ event being triggered.
     */
    $(function()
    {
        $('body').on('jdp/wp/AttachmentCentrePoint/open', function()
        {

            var candidates = document.querySelectorAll('.attachment-centre-point__trigger');
            [].slice.call(candidates).forEach(function(el)
            {
                AttachmentCentrePoint.call(el);
            });

        }).trigger('jdp/wp/AttachmentCentrePoint/open');
    });

})(jQuery);
