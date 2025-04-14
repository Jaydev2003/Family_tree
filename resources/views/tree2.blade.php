<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>

    <body>
        <div id="chartDiv" style="width: 755px;height: 300px;margin: 0px auto">
        </div>
        <script src="{{ asset('js/chart.min.js') }}"></script>
        <script src="https://code.jscharting.com/latest/jscharting.js"></script>
        <script src="https://code.jscharting.com/latest/modules/types.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
        <script>
            var chartConfig = {
                type: 'organizational down',
                defaultPoint: {
                    outline_width: 0,
                    connectorLine_width: 1,
                    color: 'white',
                    focusGlow: false,
                    tooltip: '<span style="font-size:14px"><b>%position</b></span><br><br>Team leader: <b>%name</b><br>People in the team: <b>%people</b><br>Current tasks: <b>%tasks</b><br>Tasks progress: <b>%progress%</b>',
                    annotation: {
                        padding: 5,
                        margin: [5, 15],
                        label: {
                            autoWrap: false,
                            style_fontWeight: 'normal',
                            text: '<b>%position</b><br>%name<br>' +
                                '<icon name=material/social/people size=20 color=#80cbc4> <span style="width:22px; ">%people</span>' +
                                '<icon name=material/action/check-circle size=18 color=#ffe082> <span style="width:22px; ">%tasks</span>' +
                                '<icon name=material/action/trending-up size=20 color=#ff8a80> <span style="width:26px; ">%progress%</span>'
                        }
                    }
                },
                series: [{
                    points: [{
                            name: 'Linda Moore',
                            id: 'gm',
                            attributes: {
                                position: 'General Manager',
                                people: '',
                                tasks: '',
                                progress: ''
                            },
                            tooltip: '',
                            annotation_label_text: '<span style="font-size:14px"><b>%position</b><br>%name</span>'
                        },
                        {
                            name: 'Jose Thomas',
                            id: 'seo',
                            parent: 'gm',
                            attributes: {
                                position: 'SEO and Marketing team',
                                people: 6,
                                tasks: 4,
                                progress: 41
                            }
                        },
                        {
                            name: 'Beverly Henderson',
                            id: 'dev',
                            parent: 'gm',
                            attributes: {
                                position: 'Development team',
                                people: 6,
                                tasks: 6,
                                progress: 27
                            }
                        },
                        {
                            name: 'Randy Torres',
                            id: 'dt',
                            parent: 'gm',
                            attributes: {
                                position: 'Design team',
                                people: 3,
                                tasks: 3,
                                progress: 63
                            }
                        },
                        {
                            name: 'Juan Clark',
                            id: 'ft',
                            parent: 'gm',
                            attributes: {
                                position: 'Finance/HR/Admin team',
                                people: 7,
                                tasks: 2,
                                progress: 30
                            }
                        }
                    ]
                }]
            };
            var chart = JSC.chart('chartDiv', chartConfig);
        </script>
    </body>

</html>
