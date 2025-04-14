@extends('layout.treeLayout')
@section('content')
    <link rel="stylesheet" href="{{ asset('tree/allmember.css') }}">
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script src="https://code.jscharting.com/latest/jscharting.js"></script>
    <script src="https://code.jscharting.com/latest/modules/types.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <div class="main" id="main">
        <div id="chartDiv1" class="chartDiv"></div>
        <a href="{{ route('list') }}" class="back-btn" id="backBtn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div id="error-container" class="error-container">
            <div id="error-message" class="error-message"></div>
        </div>
        <div id="customTooltip" class="custom-tooltip"></div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            var selectedPoint;
            var highlightColor = '#5C6BC0',
                mutedHighlightColor = '#0f5685',
                mutedFill = '#f3f4fa',
                selectedFill = '#E8EAF6',
                normalFill = 'white';

            var familydata = @json($formattedData);

            function flattenData(data) {
                var result = [];
                var rootNodeId = 'family_root';

                result.push({
                    id: rootNodeId,
                    name: 'rajabhai',
                    email: 'rajabhai@gmail.com',
                    phone: '8457962513',
                    address: 'vastdi',
                    gender: 'Male',
                    parent: null,
                    visible: true,
                    events: {
                        click: toggleChildren
                    }
                });

                function flatten(item, parentId = null) {
                    var point = {
                        id: item.id,
                        name: item.name,
                        wife: item.wife,
                        parent: parentId,
                        email: item.email,
                        phone: item.phone,
                        address: item.address,
                        gender: item.gender,
                        visible: false,
                        events: {
                            click: toggleChildren
                        }
                    };
                    result.push(point);
                    if (item.children && item.children.length > 0) {
                        item.children.forEach(function(child) {
                            flatten(child, item.id);
                        });
                    }
                }

                data.forEach(function(item) {
                    if (!item.parent) {
                        flatten(item, rootNodeId);
                    }
                });

                return result;
            }
            if (familydata && Array.isArray(familydata) && familydata.length > 0) {
                var points = flattenData(familydata);
            } else {
                var error = document.createElement('div');
                error.innerHTML = "<p style='color:red;'>No family data available.</p>";
                document.body.appendChild(error);
            }

            var chart = JSC.chart('chartDiv1', {
                type: 'organizational',
                defaultTooltip: {
                    enabled: false,

                },
                defaultAnnotation: {
                    padding: [5, 10],
                    margin: 15
                },
                annotations: [{
                    position: 'bottom',
                    label_text: 'Click on a node to see their children or click again to hide them.'
                }],
                defaultSeries: {
                    color: normalFill,
                    pointSelection: false
                },
                defaultPoint: {
                    focusGlow: false,
                    connectorLine: {
                        color: '#9FA8DA',
                        width: 2,
                        radius: [6, 7],

                    },
                    label: {
                        text: function(point) {
                            var name = point.options('name') || 'Unknown';
                            var wifeName = point.options('wife') || '';
                            var isMarried = wifeName !== '';
                            var maleImageSrc = '{{ asset('img/male.png') }}';
                            var femaleImageSrc = '{{ asset('img/female.png') }}';

                            if (isMarried) {
                                return `
                                    <img src="${maleImageSrc}" style="width:35px; height:35px; border-radius:50%; display:inline-block; margin:2px;">
                                    <img src="${femaleImageSrc}" style="width:35px; height:35px; border-radius:50%; display:inline-block; margin:2px;">
                                    <br>${name} & ${wifeName} 
                                `;
                            } else {
                                var imageSrc = point.options('gender') === 'Male' ? maleImageSrc :
                                    femaleImageSrc;
                                return `
                                    <img src="${imageSrc}" style="width:35px; height:35px; border-radius:50%; display:block; margin:auto;">
                                    <br>${name} <br>
                                `;
                            }
                        },
                        style_color: 'black'

                    },
                    outline: {
                        color: '#9FA8DA',
                        width: 3
                    },
                    annotation: {
                        syncHeight_with: 'level'
                    },
                    states: {
                        mute: {
                            opacity: 0.8,
                            outline: {
                                color: mutedHighlightColor,
                                opacity: 0.9,
                                width: 2
                            }
                        },
                        select: {
                            enabled: true,
                            outline: {
                                color: highlightColor,
                                width: 2
                            },
                            color: selectedFill
                        },
                        hover: {
                            outline: {
                                color: mutedHighlightColor,
                                width: 2
                            },
                            color: mutedFill
                        }
                    },
                    events: {
                        click: pointClick,
                        mouseOver: function(event) {
                            showTooltip.call(this, event);
                            pointMouseOver.call(this, event);
                        },
                        mouseOut: function(event) {
                            hideTooltip.call(this, event);
                            pointMouseOut.call(this, event);
                        }
                    }
                },
                series: [{
                    points: points.filter(p => p.visible)
                }]
            });


            function toggleChildren() {

                var point = this;
                var children = points.filter(p => p.parent === point.id);
                const errorContainer = document.getElementById('error-container');
                const errorMsg = document.getElementById('error-message');
                errorMsg.innerHTML = '';

                if (children.length === 0) {
                    errorMsg.innerHTML = 'No child record found';
                    errorMsg.style.display = 'block';

                    setTimeout(() => {
                        errorMsg.style.display = 'none';
                    }, 3000);

                    return;
                }

                var anyChildVisible = children.some(child => child.visible);

                function hideDescendants(parentId) {
                    points.forEach(child => {
                        if (child.parent === parentId) {
                            child.visible = false;
                            hideDescendants(child.id);
                        }
                    });
                }

                if (anyChildVisible) {
                    hideDescendants(point.id);
                } else {
                    children.forEach(child => {
                        child.visible = true;
                    });
                }

                points.find(p => p.id === point.id).visible = true;

                chart.series(0).options({
                    points: points.filter(p => p.visible)
                });

            }
            var tooltip = document.getElementById('customTooltip');

            function showTooltip(event) {
                var point = this;
                tooltip.style.display = 'block';
                tooltip.innerHTML = `
                            <b>Phone:</b> ${point.options('phone')}<br>
                            <b>Email:</b> ${point.options('email')}<br>
                            <b>Address:</b> ${point.options('address')}
                        `;
                document.addEventListener('mousemove', positionTooltip);
            }

            function hideTooltip() {
                tooltip.style.display = 'none';
                document.removeEventListener('mousemove', positionTooltip);
            }

            function positionTooltip(event) {
                tooltip.style.left = event.pageX + 15 + 'px';
                tooltip.style.top = event.pageY + 15 + 'px';
            }

            function pointClick() {
                var point = this,
                    chart = point.chart;
                resetStyles(chart);
                if (point.id === selectedPoint) {
                    selectedPoint = undefined;
                    return;
                }
                selectedPoint = point.id;
                styleSelectedPoint(chart);
            }

            function pointMouseOver() {
                var point = this,
                    chart = point.chart;
                chart.connectors([point.id, 'up'], {
                    color: mutedHighlightColor,
                    width: 2
                });
                chart
                    .series()
                    .points([point.id, 'up'])
                    .options({
                        muted: true
                    });
            }

            function pointMouseOut() {
                var point = this,
                    chart = point.chart;
                resetStyles(chart);
                styleSelectedPoint(chart);
                return false;
            }

            function styleSelectedPoint(chart) {
                if (selectedPoint) {
                    chart.connectors([selectedPoint, 'up'], {
                        color: highlightColor,
                        width: 2
                    });
                    chart
                        .series()
                        .points([selectedPoint, 'up'])
                        .options({
                            selected: true,
                            muted: false
                        });
                }
            }

            function resetStyles(chart) {
                chart.connectors();
                chart
                    .series()
                    .points()
                    .options({
                        selected: false,
                        muted: false
                    });
            }

            const toggleButton = document.querySelector('.toggle-sidebar-btn');
            const sidebar = document.querySelector('.sidebar');
            const backBtn = document.getElementById('backBtn');

            toggleButton.addEventListener('click', () => {
                sidebar.classList.toggle('hidden-sidebar');

                if (sidebar.classList.contains('hidden-sidebar')) {
                    backBtn.style.left = '3rem';
                } else {
                    backBtn.style.left = '22rem';
                }
            });
        });
    </script>
@endsection
