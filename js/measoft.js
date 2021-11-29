function Measoft(alowedCountries = [], sClass = '') {
    const $ = jQuery;

    $.widget('custom.measoftCombobox', {
        _create: function() {
            this.wrapper = $('<span>')
                .css({'position': 'relative', 'display': 'block'})
                .addClass('measoft-combobox')
                .insertAfter(this.element);
   
            this.element.hide();
            this._createAutocomplete();
            this._createShowAllButton();
        },
   
        _createAutocomplete: function() {
            const element  = this.element;
            const selected = this.element.children(':selected');
            const value    = selected.val() ? selected.text() : '';

            this.input = $('<input>')
                .appendTo(this.wrapper)
                .val(value)
                .attr('title', '')
                .attr('placeholder', element.attr('placeholder'))
                .css({'margin': '0', 'padding': '6px 4px', 'display': 'inline-block'})
                .addClass('measoft-combobox-input')
                .autocomplete({
                    delay:     0,
                    minLength: 0,
                    source:    $.proxy(this, '_source')
                })
                .tooltip({
                    classes: {
                        'ui-tooltip': 'ui-state-highlight'
                    }
                });
   
            this._on(this.input, {
                autocompleteselect: function(event, ui) {
                    ui.item.option.selected = true;
                    element.change();

                    this._trigger('select', event, {
                        item: ui.item.option
                    });
                },
   
                autocompletechange: '_removeIfInvalid'
            });
        },
   
        _createShowAllButton: function() {
            const input = this.input;
            let wasOpen = false;
   
            this.showAllButton = $('<a>')
                .attr('tabIndex', -1)
                //.attr('title', 'показать все')
                .tooltip()
                .appendTo(this.wrapper)
                .button({
                    icons: {primary: 'ui-icon-triangle-1-s'},
                    text: false
                })
                .removeClass('ui-corner-all')
                .css({'position': 'absolute', 'top': '0', 'bottom': '0', 'margin-left': '-1px', 'padding': '0', 'width': '30px'})
                .addClass('measoft-combobox-button ui-corner-right')
                .on('mousedown', function() {
                    wasOpen = input.autocomplete('widget').is(':visible');
                })
                .on('click', function() {
                    input.trigger('focus');
   
                    // Закрыть, если уже видно
                    if (wasOpen) return;
   
                    // Передать пустую строку в качестве значения для поиска, чтобы отобразить все результаты
                    input.autocomplete('search', '');
                });
        },
   
        _source: function(request, response) {
            const matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), 'i');

            response(this.element.children('option').map(function() {
                const text = $(this).text();
                if (this.value && (!request.term || matcher.test(text)))
                    return {
                        label:  text,
                        value:  text,
                        option: this
                    };
            }) );
        },
   
        _removeIfInvalid: function(event, ui) {
            // Выход, если элемент выбран
            if (ui.item) return;
   
            // Поиск соответствия (без учета регистра)
            const value          = this.input.val();
            const valueLowerCase = value.toLowerCase();
            let   valid          = false;

            this.element.children('option').each(function() {
                if ($(this).text().toLowerCase() === valueLowerCase) {
                    this.selected = valid = true;
                    return false;
                }
            });
   
            // Выход, если совпадение найдено
            if (valid) return;
   
            // Удалить неверное значение
            this.reset();  
            this.input
                .attr('title', 'Значение "'+ value +'" не найдено')
                .tooltip('open');
            this._delay(function() {
                this.input.tooltip('close').attr('title', '');
            }, 2500);
        },
        
        disabled: function(disabled) {
            this.input.attr('disabled', disabled);
            this.element.attr('disabled', disabled);

            if (disabled) {
                this.input.css('cursor', 'not-allowed');
                this.element.css('cursor', 'not-allowed');
                this.showAllButton.css('cursor', 'not-allowed');
            } else {
                this.input.css('cursor', 'default');
                this.element.css('cursor', 'default');
                this.showAllButton.css('cursor', 'default');
            }
        },
        
        reset: function() {
            this.input.val('');
            this.element.val('');
            this.input.autocomplete('instance').term = '';
        },
   
        _destroy: function() {
            this.wrapper.remove();
            this.element.show();
        }
    });
    
    class Api {
        constructor(startTag) {
            this._startTag = startTag;
            this._xml      = this._createXml(startTag);
        }
        
        _createXml(startTag) {
            return $($.parseXML('<?xml version="1.0" encoding="UTF-8"?><'+ startTag +'/>'));
        }
        
        request() {
            const $xml  = this.buildXml();
            const $ajax = $.ajax({
                type:     'POST',
                url:      'https://home.courierexe.ru/api/',
                data:     (new XMLSerializer()).serializeToString($xml[0]),
                dataType: 'xml'
            });
            
            $ajax.always(function(resp) {
                console.log(resp);
            });
            
            return $ajax;
        }
    }

    class SearchHandler extends Api {
        constructor(startTag) {
            super(startTag);
            this._codesearch = {};
            this._conditions = {};
            this._limit      = {};
        }
        
        buildXml() {
            const $startTag = $(this._startTag, this._xml);
            
            if (!$.isEmptyObject(this._codesearch)) {
                $startTag.append($('<codesearch/>', this._xml));

                const $codesearch = $('codesearch', $startTag);
                
                $.each(this._codesearch, function(key, value) {
                    $codesearch.append(value);
                });
            }
            
            if (!$.isEmptyObject(this._conditions)) {
                $startTag.append($('<conditions/>', this._xml));

                const $conditions = $('conditions', $startTag);
                
                $.each(this._conditions, function(key, value) {
                    $conditions.append(value);
                });
            }
            
            if (!$.isEmptyObject(this._limit)) {
                $startTag.append($('<limit/>', this._xml));

                const $limit = $('limit', $startTag);
                
                $.each(this._limit, function(key, value) {
                    $limit.append(value);
                });
            }
            
            //console.log(this._xml[0]);
            
            return this._xml;
        }
    }

    class RegionSearch extends SearchHandler {
        constructor() {
            super('regionlist');
        }
        
        code(code) {
            this._codesearch.code = $('<code/>', this._xml).text(code);
            return this;
        }
        
        country(country) {
            this._conditions.country = $('<country/>', this._xml).text(country);
            return this;
        }
    }

    class TownSearch extends SearchHandler {
        constructor() {
            super('townlist');
        }
        
        country(country) {
            this._conditions.country = $('<country/>', this._xml).text(country);
            return this;
        }
        
        region(region) {
            this._conditions.region = $('<city/>', this._xml).text(region);
            return this;
        }
        
        limit(limit) {
            this._limit.limitcount = $('<limitcount/>', this._xml).text(limit);
            return this;
        }
    }

    class StreetSearch extends SearchHandler {
        constructor() {
            super('streetlist');
        }
        
        code(code) {
            this._codesearch.code = $('<code/>', this._xml).text(code);
            return this;
        }
        
        town(town) {
            this._conditions.town = $('<town/>', this._xml).text(town);
            return this;
        }
    }

    class Calculator extends Api {
        constructor() {
            super('calculator');
            this._calc = {};
            this._auth = {};
        }
        
        townFrom(townFrom) {
            this._calc.townfrom = townFrom;
            return this;
        }
        
        townTo(townTo) {
            this._calc.townto = townTo;
            return this;
        }
        
        length(length) {
            this._calc.l = length;
            return this;
        }
        
        width(width) {
            this._calc.w = width;
            return this;
        }
        
        height(height) {
            this._calc.h = height;
            return this;
        }
        
        weight(weight) {
            this._calc.mass = weight;
            return this;
        }
        
        service(service) {
            this._calc.service = service;
            return this;
        }

        authExtra(authExtra) {
            this._auth.extra = authExtra;
            return this;
        }

        buildXml() {
            if ($.isEmptyObject(this._calc) || $.isEmptyObject(this._auth)) {
                console.log('empty');
                return null;
            }

            $(this._startTag, this._xml)
                .append($('<calc/>', this._xml).attr(this._calc))
                .append($('<auth/>', this._xml).attr(this._auth));
            
            console.log(this._xml[0]);
            
            return this._xml;
        }
    }

    this.getCalculator = function(weight) {
        return new Calculator();
    }

    const $countrySelect = this.countrySelect = $('.measoft-countries '+ sClass);
    const $regionSelect  = this.regionSelect  = $('.measoft-regions '+ sClass);
    const $townSelect    = this.townSelect    = $('.measoft-towns '+ sClass);
    const $streetSelect  = this.streetSelect  = $('.measoft-streets '+ sClass);
    const $serviceSelect = this.serviceSelect = $('.measoft-services '+ sClass);

    $countrySelect.html('<option></option>');
    const $ajax = (new RegionSearch()).request();

    $ajax.always(function(response) {
        let countries = {};

        $(response).find('city').each(function(){
            const $region = $(this);
            const name    = $region.children('country').children('name').text();
            const code    = $region.children('country').children('code').text();

            countries[code] = name;
        });

        $.each(countries, function(code, name) {
            if (
                alowedCountries.length &&
                $.inArray(code, alowedCountries) < 0 &&
                $.inArray(Number(code), alowedCountries) < 0 &&
                $.inArray(name, alowedCountries) < 0
            ) {
                return;
            }

            $countrySelect.append('<option value="'+ code +'">'+ name +'</option>');
        });
    });

    $countrySelect.on('change', function() {
        $regionSelect.html('<option></option>').measoftCombobox('reset');
        $townSelect.empty().measoftCombobox('reset').measoftCombobox('disabled', true);
        $streetSelect.empty().measoftCombobox('reset').measoftCombobox('disabled', true);
        $serviceSelect.empty().measoftCombobox('reset').measoftCombobox('disabled', true);

        const $ajax = (new RegionSearch()).country($(':selected', this).val()).request();

        $ajax.always(function(response) {
            $(response).find('city').each(function(){
                const $region = $(this);
                const name    = $region.children('name').text();
                const code    = $region.children('code').text();

                $regionSelect.append('<option value="'+ code +'">'+ name +'</option>');
            });
            $regionSelect.measoftCombobox('disabled', false);
        });
    });

    $regionSelect.on('change', function() {
        $townSelect.html('<option></option>').measoftCombobox('reset');
        $streetSelect.empty().measoftCombobox('reset').measoftCombobox('disabled', true);
        $serviceSelect.empty().measoftCombobox('reset').measoftCombobox('disabled', true);

        const $ajax = (new TownSearch()).region($(':selected', this).val()).request();

        $ajax.always(function(response) {
            $(response).find('town').each(function(){
                const $town = $(this);
                const name  = $town.children('name').text();
                const code  = $town.children('code').text();

                $townSelect.append('<option value="'+ code +'">'+ name +'</option>');
            });
            $townSelect.measoftCombobox('disabled', false);
        });
    });

    $townSelect.on('change', function() {
        $streetSelect.html('<option></option>').measoftCombobox('reset');
        $serviceSelect.html('<option></option>').measoftCombobox('reset');

        const $ajax = (new StreetSearch()).town($(':selected', this).text()).request();

        $ajax.always(function(response) {
            $(response).find('street').each(function() {
                const $street = $(this);
                const name    = $street.children('name').text();

                $streetSelect.append('<option value="'+ name +'">'+ name +'</option>');
            });
            $streetSelect.measoftCombobox('disabled', false);
        });
    });
    
    $countrySelect.measoftCombobox();
    $regionSelect.measoftCombobox().measoftCombobox('disabled', true);
    $townSelect.measoftCombobox().measoftCombobox('disabled', true);
    $streetSelect.measoftCombobox().measoftCombobox('disabled', true);
    $serviceSelect.measoftCombobox().measoftCombobox('disabled', true);
    
    return this;
}