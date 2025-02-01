

<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/ard/ard" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<div id="report_btn" style="font-size: large; float: right;">
    <a href="/show/report/ard/ard_report" title="Report">
        <i class="btn btn-default tab-btn fa fa-th"></i>
    </a>
</div>
<h2 data-i18n="ard.ard"></h2>
<div id="ard-tab"></div>

<div id="ard-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
    var $ardMsg = $('#ard-msg');
    var $ardTab = $('#ard-tab');

    $.getJSON(appUrl + '/module/ard/get_tab_data/' + serialNumber, function(data){
        
        if (!data) {
            $ardMsg.text(i18n.t('no_data'));
        } else {
            $ardMsg.text('');
            $('#ard-count-view').removeClass('hide');

            var skipThese = ['id', 'serial_number', 'admin_machines'];
            var rows = '';
            var administratorsRows = '';
            var taskServersRows = '';

            $.each(data, function(i, d){
                for (var prop in d) {
                    if (skipThese.indexOf(prop) !== -1 || d[prop] === '' || d[prop] === null || d[prop] === "{}") {
                        continue;
                    }

                    var value = d[prop];
                    var translatedProp = i18n.t('ard.' + prop);

                    if (['screensharing_request_permission', 'load_menu_extra', 'console_allows_remote', 'allow_all_local_users', 'directory_login'].includes(prop)) {
                        rows += '<tr><th>' + translatedProp + '</th><td>' + i18n.t(value == "yes" || value == 1 ? 'yes' : 'no') + '</td></tr>';
                    } else if (prop === 'vnc_enabled') {
                        rows += '<tr><th>' + translatedProp + '</th><td>' + i18n.t(value == "yes" || value == 1 ? 'enabled' : 'disabled') + '</td></tr>';
                    } else if (prop === 'administrators') {
                        administratorsRows = buildAdministratorsTable(JSON.parse(value));
                    } else if (prop === 'task_servers') {
                        taskServersRows = buildTaskServersTable(JSON.parse(value));
                    } else {
                        rows += '<tr><th style="width: 165px;">' + translatedProp + '</th><td style="max-width: 500px;">' + value + '</td></tr>';
                    }
                }
            });

            $ardTab.append(createTable(rows, 500));

            if (administratorsRows) {
                $ardTab.append('<h4>' + i18n.t('ard.administrators') + '</h4>')
                       .append(createTable(administratorsRows, 985));
            } else {
                $ardTab.append('<br>');
            }

            if (taskServersRows) {
                $ardTab.append('<h4>' + i18n.t('ard.task_servers') + '</h4>')
                       .append(createTable(taskServersRows, 600));
            } else {
                $ardTab.append('<br>');
            }
        }
    });

    function createTable(rows, width) {
        return $('<div>').append($('<table style="width: ' + width + 'px;">')
            .addClass('table table-striped table-condensed')
            .append($('<tbody>').append(rows)));
    }

    function buildAdministratorsTable(data) {
        var rows = '<tr><th style="max-width: 140px;">' + i18n.t('ard.computername') + '</th><th style="max-width: 85px;">' + i18n.t('ard.has_latest_reporting_info') + '</th><th style="max-width: 60px;">' + i18n.t('ard.mac_address') + '</th><th style="max-width: 300px;">' + i18n.t('ard.task_server_id') + '</th><th style="max-width: 300px;">' + i18n.t('ard.ip_address') + '</th><th style="max-width: 300px;">' + i18n.t('ard.port') + '</th><th style="max-width: 300px;">' + i18n.t('ard.last_contact') + '</th></tr>';
        $.each(data, function(i, d) {
            var hasLatestReportingInfo = d['HasLatestReportingInfo'] == 1 ? i18n.t('yes') : i18n.t('no');
            var lastContact = d['LastContact'] ? '<span title="' + moment(new Date(d['LastContact'] * 1000)).fromNow() + '">' + moment(new Date(d['LastContact'] * 1000)).format('llll') + '</span>' : '';
            rows += '<tr><td>' + (d['ComputerName'] || '') + '</td><td>' + hasLatestReportingInfo + '</td><td>' + (d['MAC_address'] || '') + '</td><td>' + (d['TaskServerID'] || '') + '</td><td>' + (d['IPAddress'] || '') + '</td><td>' + (d['Port'] || '') + '</td><td>' + lastContact + '</td></tr>';
        });
        return rows;
    }

    function buildTaskServersTable(data) {
        var rows = '<tr><th style="max-width: 240px;">' + i18n.t('ard.dns_name') + '</th><th style="max-width: 60px;">' + i18n.t('ard.mac_address') + '</th><th style="max-width: 300px;">' + i18n.t('ard.ip_address') + '</th><th style="max-width: 300px;">' + i18n.t('ard.port') + '</th></tr>';
        $.each(data, function(i, d) {
            rows += '<tr><td>' + (d['DNSName'] || '') + '</td><td>' + (d['MAC_address'] || '') + '</td><td>' + (d['IPAddress'] || '') + '</td><td>' + (d['Port'] || '') + '</td></tr>';
        });
        return rows;
    }
});
</script>
