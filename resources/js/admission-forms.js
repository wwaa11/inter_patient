window.escapeHtml = function(s) { return (s + '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); };

// Helper functions for search-add components
window.initSearchAdd_getSelectedIds = function($targetSelected, nameAttr) {
    return $targetSelected.find('input[name="' + nameAttr + '"]').map(function() { return this.value; }).get();
}
// For multi-selects only - to prevent duplicates
window.initSearchAdd_addMultiSelectItem = function($targetSelected, id, name, nameAttr, chipClass, removeBtnClass) {
    if (window.initSearchAdd_getSelectedIds($targetSelected, nameAttr).indexOf(String(id)) !== -1) return; 
    var chip = '<span class="' + chipClass + ' inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">' +
        window.escapeHtml(name) + ' <button type="button" class="' + removeBtnClass + ' rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800" aria-label="Remove">&times;</button>' +
        '<input type="hidden" name="' + nameAttr + '" value="' + window.escapeHtml(String(id)) + '"></span>';
    $targetSelected.append(chip);
}
// For single-selects or pre-filling - clears existing and adds new
window.initSearchAdd_addItem = function($targetSelected, id, name, nameAttr, chipClass, removeBtnClass) {
    $targetSelected.empty();
    if (id == null || id === '') {
        window.initSearchAdd_setEmpty($targetSelected, nameAttr);
        return;
    }
    var chip = '<span class="' + chipClass + ' inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">' +
        escapeHtml(name) + ' <button type="button" class="' + removeBtnClass + ' rounded-full p-0.5 hover:bg-emerald-200 dark:hover:bg-emerald-800" aria-label="Remove">&times;</button>' +
        '<input type="hidden" name="' + nameAttr + '" value="' + escapeHtml(String(id)) + '"></span>';
    $targetSelected.append(chip);
};
window.initSearchAdd_setEmpty = function($targetSelected, nameAttr) {
    $targetSelected.empty();
    $targetSelected.append('<input type="hidden" name="' + nameAttr + '" value="" id="' + nameAttr.replace(/[^\w]/g, '') + '_empty_input">');
};

window.initSearchAdd = function(opts) {
    var dataEl = document.getElementById(opts.dataElId);
    if (!dataEl) return;
    var list = JSON.parse(dataEl.textContent);
    var $search = $('#' + opts.searchInputId);
    var $results = $('#' + opts.resultsDivId);
    var $selected = $('#' + opts.selectedDivId);

    function renderResults(q) {
        var ids = window.initSearchAdd_getSelectedIds($selected, opts.nameAttr);
        var lower = (q || '').toLowerCase();
        var matchUserid = opts.searchByUserid === true;
        var filtered = list.filter(function(item) {
            return ids.indexOf(String(item.id)) === -1 && (!lower || item.name.toLowerCase().indexOf(lower) !== -1 || (matchUserid && item.userid && String(item.userid).toLowerCase().indexOf(lower) !== -1));
        });
        if (filtered.length === 0) {
            $results.hide().empty();
            return;
        }
        var html = '';
        var showUserid = opts.showUserid === true;
        filtered.forEach(function(item) {
            var label = window.escapeHtml(item.name) + (showUserid ? ' <span class="text-slate-400">(User ID: ' + window.escapeHtml(String(item.userid || '')) + ')</span>' : '');
            html += '<div class="search-add-row cursor-pointer px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700" data-id="' + window.escapeHtml(String(item.id)) + '" data-name="' + window.escapeHtml(item.name) + '">' + label + '</div>';
        });
        $results.html(html).show();
    }

    $search.on('input focus', function() { renderResults($(this).val()); });
    $search.on('blur', function() { setTimeout(function() { $results.hide(); }, 200); });
    $results.on('click', '.search-add-row', function() {
        window.initSearchAdd_addMultiSelectItem($selected, $(this).data('id'), $(this).data('name'), opts.nameAttr, opts.chipClass, opts.removeBtnClass);
        $search.val('');
        renderResults('');
    });
    $selected.on('click', '.' + opts.removeBtnClass, function(e) {
        e.preventDefault();
        $(this).closest('span').remove();
        if (window.initSearchAdd_getSelectedIds($selected, opts.nameAttr).length === 0) {
            window.initSearchAdd_setEmpty($selected, opts.nameAttr);
        }
    });
}

$(document).ready(function() {
    initSearchAdd({ dataElId: 'contact_providers_data', searchInputId: 'contact_provider_search', resultsDivId: 'contact_provider_results', selectedDivId: 'contact_provider_selected', nameAttr: 'contact_providers[]', chipClass: 'contact-provider-chip', removeBtnClass: 'contact-provider-remove' });
    initSearchAdd({ dataElId: 'handling_users_data', searchInputId: 'handling_user_search', resultsDivId: 'handling_user_results', selectedDivId: 'handling_user_selected', nameAttr: 'handling_users[]', chipClass: 'handling-user-chip', removeBtnClass: 'handling-user-remove', showUserid: true, searchByUserid: true });
    initSearchAdd({ dataElId: 'gop_translators_data', searchInputId: 'gop_translator_search', resultsDivId: 'gop_translator_results', selectedDivId: 'gop_translator_selected', nameAttr: 'gop_translators[]', chipClass: 'gop-translator-chip', removeBtnClass: 'gop-translator-remove', showUserid: true, searchByUserid: true });
});