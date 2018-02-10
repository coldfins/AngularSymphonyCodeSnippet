var Sidebar = function (element, options) {
    this.$element = $(element)
    this.options = $.extend({}, Sidebar.DEFAULTS, options)
    this.transitioning = null

    if (this.options.parent)
        this.$parent = $(this.options.parent)
    if (this.options.toggle)
        this.toggle()

}

Sidebar.DEFAULTS = {
    toggle: true
}

Sidebar.prototype.show = function () {
    if (this.transitioning || this.$element.hasClass('sidebar-open'))
        return


    var startEvent = $.Event('show.bs.sidebar')
    this.$element.trigger(startEvent);
    if (startEvent.isDefaultPrevented())
        return

    this.$element
            .addClass('sidebar-open')

    $('body').css({'overflow': 'hidden', 'position': 'fixed'})
    $('body').append('<div class="op_effect" style="position: fixed;top: 0px;bottom: 0px;left: 0px;right: 0px;height: 150%;background: black;width: 150%;z-index: 1;opacity: 0.7;"></div>');



    this.transitioning = 1



    var complete = function () {
        this.$element
        this.transitioning = 0
        this.$element.trigger('shown.bs.sidebar')
    }

    if (!$.support.transition)
        return complete.call(this)

    this.$element
            .one($.support.transition.end, $.proxy(complete, this))
            .emulateTransitionEnd(400)
}

Sidebar.prototype.hide = function () {
    if (this.transitioning || !this.$element.hasClass('sidebar-open'))
        return

    var startEvent = $.Event('hide.bs.sidebar')
    this.$element.trigger(startEvent)
    if (startEvent.isDefaultPrevented())
        return

    this.$element
            .removeClass('sidebar-open')

    $('body').css({'overflow': 'auto', 'position': 'relative'})
    $('.op_effect').remove()

    this.transitioning = 1

    var complete = function () {
        this.transitioning = 0
        this.$element
                .trigger('hidden.bs.sidebar')
    }

    if (!$.support.transition)
        return complete.call(this)

    this.$element
            .one($.support.transition.end, $.proxy(complete, this))
            .emulateTransitionEnd(400)
}

Sidebar.prototype.toggle = function () {
    this[this.$element.hasClass('sidebar-open') ? 'hide' : 'show']()
}

var old = $.fn.sidebar

$.fn.sidebar = function (option) {
    return this.each(function () {
        var $this = $(this)
        var data = $this.data('bs.sidebar')
        var options = $.extend({}, Sidebar.DEFAULTS, $this.data(), typeof options == 'object' && option)

        if (!data && options.toggle && option == 'show')
            option = !option
        if (!data)
            $this.data('bs.sidebar', (data = new Sidebar(this, options)))
        if (typeof option == 'string')
            data[option]()
    })
}

$.fn.sidebar.Constructor = Sidebar

$(document).on('click.bs.sidebar.data-api', '[data-toggle="sidebar"]', function (e) {
    var $this = $(this), href
    var target = $this.attr('data-target')
            || e.preventDefault()
            || (href = $this.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, '')
    var $target = $(target)
    var data = $target.data('bs.sidebar')
    var option = data ? 'toggle' : $this.data()

    $target.sidebar(option)
})

$('html').on('click.bs.sidebar.autohide', function (event) {
    var $this = $(event.target);
    var isButtonOrSidebar = $this.is('.sidebar, [data-toggle="sidebar"]') || $this.parents('.sidebar, [data-toggle="sidebar"]').length;
    if (isButtonOrSidebar) {
        return;
    } else {
        var $target = $('.sidebar');
        $target.each(function (i, trgt) {
            var $trgt = $(trgt);
            if ($trgt.data('bs.sidebar') && $trgt.hasClass('sidebar-open')) {
                $trgt.sidebar('hide');
            }
        })
    }
});