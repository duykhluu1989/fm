<?php

namespace App\Libraries\Helpers;

class Area
{
    public static $provinces = [
        'HANOI' => [
            'name' => 'Hà Nội',
            'cities' => [
                'QUANBADINH' => 'Quận Ba Đình',
                'QUANHOANKIEM' => 'Quận Hoàn Kiếm',
                'QUANHAIBATRUNG' => 'Quận Hai Bà Trưng',
                'QUANDONGDA' => 'Quận Đống Đa',
                'QUANTAYHO' => 'Quận Tây Hồ',
                'QUANCAUGIAY' => 'Quận Cầu Giấy',
                'QUANTHANHXUAN' => 'Quận Thanh Xuân',
                'QUANHOANGMAI' => 'Quận Hoàng Mai',
                'QUANLONGBIEN' => 'Quận Long Biên',
                'HUYENTULIEM' => 'Huyện Từ Liêm',
                'HUYENTHANHTRI' => 'Huyện Thanh Trì',
                'HUYENGIALAM' => 'Huyện Gia Lâm',
                'HUYENDONGANH' => 'Huyện Đông Anh',
                'HUYENSOCSON' => 'Huyện Sóc Sơn',
                'QUANHADONG' => 'Quận Hà Đông',
                'THIXASONTAY' => 'Thị xã Sơn Tây',
                'HUYENBAVI' => 'Huyện Ba Vì',
                'HUYENPHUCTHO' => 'Huyện Phúc Thọ',
                'HUYENTHACHTHAT' => 'Huyện Thạch Thất',
                'HUYENQUOCOAI' => 'Huyện Quốc Oai',
                'HUYENCHUONGMY' => 'Huyện Chương Mỹ',
                'HUYENDANPHUONG' => 'Huyện Đan Phượng',
                'HUYENHOAIDUC' => 'Huyện Hoài Đức',
                'HUYENTHANHOAI' => 'Huyện Thanh Oai',
                'HUYENMYDUC' => 'Huyện Mỹ Đức',
                'HUYENUNGHOA' => 'Huyện Ứng Hoà',
                'HUYENTHUONGTIN' => 'Huyện Thường Tín',
                'HUYENPHUXUYEN' => 'Huyện Phú Xuyên',
                'HUYENMELINH' => 'Huyện Mê Linh',
                'HANOIKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'HOCHIMINH' => [
            'name' => 'Hồ Chí Minh',
            'cities' => [
                'QUAN1' => [
                    'name' => 'Quận 1',
                    'price' => 20000,
                ],
                'QUAN2' => [
                    'name' => 'Quận 2',
                    'price' => 25000,
                ],
                'QUAN3' => [
                    'name' => 'Quận 3',
                    'price' => 20000,
                ],
                'QUAN4' => [
                    'name' => 'Quận 4',
                    'price' => 20000,
                ],
                'QUAN5' => [
                    'name' => 'Quận 5',
                    'price' => 20000,
                ],
                'QUAN6' => [
                    'name' => 'Quận 6',
                    'price' => 20000,
                ],
                'QUAN7' => [
                    'name' => 'Quận 7',
                    'price' => 20000,
                ],
                'QUAN8' => [
                    'name' => 'Quận 8',
                    'price' => 20000,
                ],
                'QUAN9' => [
                    'name' => 'Quận 9',
                    'price' => 25000,
                ],
                'QUAN10' => [
                    'name' => 'Quận 10',
                    'price' => 20000,
                ],
                'QUAN11' => [
                    'name' => 'Quận 11',
                    'price' => 20000,
                ],
                'QUAN12' => [
                    'name' => 'Quận 12',
                    'price' => 25000,
                ],
                'QUANGOVAP' => [
                    'name' => 'Quận Gò Vấp',
                    'price' => 25000,
                ],
                'QUANTANBINH' => [
                    'name' => 'Quận Tân Bình',
                    'price' => 20000,
                ],
                'QUANTANPHU' => [
                    'name' => 'Quận Tân Phú',
                    'price' => 25000,
                ],
                'QUANBINHTHANH' => [
                    'name' => 'Quận Bình Thạnh',
                    'price' => 20000,
                ],
                'QUANPHUNHUAN' => [
                    'name' => 'Quận Phú Nhuận',
                    'price' => 20000,
                ],
                'QUANTHUDUC' => [
                    'name' => 'Quận Thủ Đức',
                    'price' => 25000,
                ],
                'QUANBINHTAN' => [
                    'name' => 'Quận Bình Tân',
                    'price' => 25000,
                ],
                'HUYENBINHCHANH' => [
                    'name' => 'Huyện Bình Chánh',
                    'price' => 40000,
                ],
                'HUYENCUCHI' => 'Huyện Củ Chi',
                'HUYENHOCMON' => [
                    'name' => 'Huyện Hóc Môn',
                    'price' => 40000,
                ],
                'HUYENNHABE' => [
                    'name' => 'Huyện Nhà Bè',
                    'price' => 40000,
                ],
                'HUYENCANGIO' => 'Huyện Cần Giờ',
                'HOCHIMINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'HAIPHONG' => [
            'name' => 'Hải Phòng',
            'cities' => [
                'QUANHONGBANG' => 'Quận Hồng Bàng',
                'QUANLECHAN' => 'Quận Lê Chân',
                'QUANNGOQUYEN' => 'Quận Ngô Quyền',
                'QUANKIENAN' => 'Quận Kiến An',
                'QUANHAIAN' => 'Quận Hải An',
                'QUANDOSON' => 'Quận Đồ Sơn',
                'HUYENANLAO' => 'Huyện An Lão',
                'HUYENKIENTHUY' => 'Huyện Kiến Thụy',
                'HUYENTHUYNGUYEN' => 'Huyện Thủy Nguyên',
                'HUYENANDUONG' => 'Huyện An Dương',
                'HUYENTIENLANG' => 'Huyện Tiên Lãng',
                'HUYENVINHBAO' => 'Huyện Vĩnh Bảo',
                'HUYENCATHAI' => 'Huyện Cát Hải',
                'HUYENBACHLONGVI' => 'Huyện Bạch Long Vĩ',
                'QUANDUONGKINH' => 'Quận Dương Kinh',
                'HAIPHONGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'DANANG' => [
            'name' => 'Đà Nẵng',
            'cities' => [
                'QUANHAICHAU' => 'Quận Hải Châu',
                'QUANTHANHKHE' => 'Quận Thanh Khê',
                'QUANSONTRA' => 'Quận Sơn Trà',
                'QUANNGUHANHSON' => 'Quận Ngũ Hành Sơn',
                'QUANLIENCHIEU' => 'Quận Liên Chiểu',
                'HUYENHOAVANG' => 'Huyện Hoà Vang',
                'QUANCAMLE' => 'Quận Cẩm Lệ',
                'DANANGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'HAGIANG' => [
            'name' => 'Hà Giang',
            'cities' => [
                'THANHPHOHAGIANG' => 'Thành phố Hà Giang',
                'HUYENDONGVAN' => 'Huyện Đồng Văn',
                'HUYENMEOVAC' => 'Huyện Mèo Vạc',
                'HUYENYENMINH' => 'Huyện Yên Minh',
                'HUYENQUANBA' => 'Huyện Quản Bạ',
                'HUYENVIXUYEN' => 'Huyện Vị Xuyên',
                'HUYENBACME' => 'Huyện Bắc Mê',
                'HUYENHOANGSUPHI' => 'Huyện Hoàng Su Phì',
                'HUYENXINMAN' => 'Huyện Xín Mần',
                'HUYENBACQUANG' => 'Huyện Bắc Quang',
                'HUYENQUANGBINH' => 'Huyện Quang Bình',
                'HAGIANGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'CAOBANG' => [
            'name' => 'Cao Bằng',
            'cities' => [
                'THANHPHOCAOBANG' => 'Thành phố Cao Bằng',
                'HUYENBAOLAC' => 'Huyện Bảo Lạc',
                'HUYENTHONGNONG' => 'Huyện Thông Nông',
                'HUYENHAQUANG' => 'Huyện Hà Quảng',
                'HUYENTRALINH' => 'Huyện Trà Lĩnh',
                'HUYENTRUNGKHANH' => 'Huyện Trùng Khánh',
                'HUYENNGUYENBINH' => 'Huyện Nguyên Bình',
                'HUYENHOAAN' => 'Huyện Hoà An',
                'HUYENQUANGUYEN' => 'Huyện Quảng Uyên',
                'HUYENTHACHAN' => 'Huyện Thạch An',
                'HUYENHALANG' => 'Huyện Hạ Lang',
                'HUYENBAOLAM' => 'Huyện Bảo Lâm',
                'HUYENPHUCHOA' => 'Huyện Phục Hoà',
                'CAOBANGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'LAICHAU' => [
            'name' => 'Lai Châu',
            'cities' => [
                'THANHPHOLAICHAU' => 'Thành Phố Lai Châu',
                'HUYENTAMDUONG' => 'Huyện Tam Đường',
                'HUYENPHONGTHO' => 'Huyện Phong Thổ',
                'HUYENSINHO' => 'Huyện Sìn Hồ',
                'HUYENMUONGTE' => 'Huyện Mường Tè',
                'HUYENTHANUYEN' => 'Huyện Than Uyên',
                'HUYENTANUYEN' => 'Huyện Tân Uyên',
                'HUYENNAMNHUM' => 'Huyện Nậm Nhùm',
                'LAICHAUKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'LAOCAI' => [
            'name' => 'Lào Cai',
            'cities' => [
                'THANHPHOLAOCAI' => 'Thành phố Lào Cai',
                'HUYENXIMACAI' => 'Huyện Xi Ma Cai',
                'HUYENBATXAT' => 'Huyện Bát Xát',
                'HUYENBAOTHANG' => 'Huyện Bảo Thắng',
                'HUYENSAPA' => 'Huyện Sa Pa',
                'HUYENVANBAN' => 'Huyện Văn Bàn',
                'HUYENBAOYEN' => 'Huyện Bảo Yên',
                'HUYENBACHA' => 'Huyện Bắc Hà',
                'HUYENMUONGKHUONG' => 'Huyện Mường Khương',
                'LAOCAIKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'TUYENQUANG' => [
            'name' => 'Tuyên Quang',
            'cities' => [
                'TH.PHOTUYENQUANG' => 'Th. phố Tuyên Quang',
                'HUYENLAMBINH' => 'Huyện Lâm Bình',
                'HUYENNAHANG' => 'Huyện Na Hang',
                'HUYENCHIEMHOA' => 'Huyện Chiêm Hoá',
                'HUYENHAMYEN' => 'Huyện Hàm Yên',
                'HUYENYENSON' => 'Huyện Yên Sơn',
                'HUYENSONDUONG' => 'Huyện Sơn Dương',
                'TUYENQUANGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'LANGSON' => [
            'name' => 'Lạng Sơn',
            'cities' => [
                'THANHPHOLANGSON' => 'Thành phố Lạng Sơn',
                'HUYENTRANGDINH' => 'Huyện Tràng Định',
                'HUYENBINHGIA' => 'Huyện Bình Gia',
                'HUYENVANLANG' => 'Huyện Văn Lãng',
                'HUYENBACSON' => 'Huyện Bắc Sơn',
                'HUYENVANQUAN' => 'Huyện Văn Quan',
                'HUYENCAOLOC' => 'Huyện Cao Lộc',
                'HUYENLOCBINH' => 'Huyện Lộc Bình',
                'HUYENCHILANG' => 'Huyện Chi Lăng',
                'HUYENDINHLAP' => 'Huyện Đình Lập',
                'HUYENHUULUNG' => 'Huyện Hữu Lũng',
                'LANGSONKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BACKAN' => [
            'name' => 'Bắc Kạn',
            'cities' => [
                'THIXABACKAN' => 'Thị xã Bắc Kạn',
                'HUYENCHODON' => 'Huyện Chợ Đồn',
                'HUYENBACHTHONG' => 'Huyện Bạch Thông',
                'HUYENNARI' => 'Huyện Na Rì',
                'HUYENNGANSON' => 'Huyện Ngân Sơn',
                'HUYENBABE' => 'Huyện Ba Bể',
                'HUYENCHOMOI' => 'Huyện Chợ Mới',
                'HUYENPACNAM' => 'Huyện Pác Nặm',
                'BACKANKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'THAINGUYEN' => [
            'name' => 'Thái Nguyên',
            'cities' => [
                'THANHPHOTHAINGUYEN' => 'Thành phố Thái Nguyên',
                'THIXASONGCONG' => 'Thị xã Sông Công',
                'HUYENDINHHOA' => 'Huyện Định Hoá',
                'HUYENPHULUONG' => 'Huyện Phú Lương',
                'HUYENVONHAI' => 'Huyện Võ Nhai',
                'HUYENDAITU' => 'Huyện Đại Từ',
                'HUYENDONGHY' => 'Huyện Đồng Hỷ',
                'HUYENPHUBINH' => 'Huyện Phú Bình',
                'HUYENPHOYEN' => 'Huyện Phổ Yên',
                'THAINGUYENKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'YENBAI' => [
            'name' => 'Yên Bái',
            'cities' => [
                'THANHPHOYENBAI' => 'Thành phố Yên Bái',
                'THIXANGHIALO' => 'Thị xã Nghĩa Lộ',
                'HUYENVANYEN' => 'Huyện Văn Yên',
                'HUYENYENBINH' => 'Huyện Yên Bình',
                'HUYENMUCANGCHAI' => 'Huyện Mù Cang Chải',
                'HUYENVANCHAN' => 'Huyện Văn Chấn',
                'HUYENTRANYEN' => 'Huyện Trấn Yên',
                'HUYENTRAMTAU' => 'Huyện Trạm Tấu',
                'HUYENLUCYEN' => 'Huyện Lục Yên',
                'YENBAIKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'SONLA' => [
            'name' => 'Sơn La',
            'cities' => [
                'THANHPHOSONLA' => 'Thành phố Sơn La',
                'HUYENQUYNHNHAI' => 'Huyện Quỳnh Nhai',
                'HUYENMUONGLA' => 'Huyện Mường La',
                'HUYENTHUANCHAU' => 'Huyện Thuận Châu',
                'HUYENBACYEN' => 'Huyện Bắc Yên',
                'HUYENPHUYEN' => 'Huyện Phù Yên',
                'HUYENMAISON' => 'Huyện Mai Sơn',
                'HUYENYENCHAU' => 'Huyện Yên Châu',
                'HUYENSONGMA' => 'Huyện Sông Mã',
                'HUYENMOCCHAU' => 'Huyện Mộc Châu',
                'HUYENSOPCOP' => 'huyện Sốp Cộp',
                'HUYENVANHO' => 'Huyện Vân Hồ',
                'SONLAKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'PHUTHO' => [
            'name' => 'Phú Thọ',
            'cities' => [
                'THANHPHOVIETTRI' => 'Thành phố Việt Trì',
                'THIXAPHUTHO' => 'Thị xã Phú Thọ',
                'HUYENDOANHUNG' => 'Huyện Đoan Hùng',
                'HUYENTHANHBA' => 'Huyện Thanh Ba',
                'HUYENHAHOA' => 'Huyện Hạ Hoà',
                'HUYENCAMKHE' => 'Huyện Cẩm Khê',
                'HUYENYENLAP' => 'Huyện Yên Lập',
                'HUYENTHANHSON' => 'Huyện Thanh Sơn',
                'HUYENPHUNINH' => 'Huyện Phù Ninh',
                'HUYENLAMTHAO' => 'Huyện Lâm Thao',
                'HUYENTAMNONG' => 'Huyện Tam Nông',
                'HUYENTHANHTHUY' => 'Huyện Thanh Thủy',
                'HUYENTANSON' => 'Huyện Tân Sơn',
                'PHUTHOKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'VINHPHUC' => [
            'name' => 'Vĩnh Phúc',
            'cities' => [
                'THANHPHOVINHYEN' => 'Thành phố Vĩnh Yên',
                'HUYENTAMDUONG' => 'Huyện Tam Dương',
                'HUYENLAPTHACH' => 'Huyện Lập Thạch',
                'HUYENVINHTUONG' => 'Huyện Vĩnh Tường',
                'HUYENYENLAC' => 'Huyện Yên Lạc',
                'HUYENBINHXUYEN' => 'Huyện Bình Xuyên',
                'HUYENSONGLO' => 'Huyện Sông Lô',
                'THIXAPHUCYEN' => 'Thị xã Phúc Yên',
                'HUYENTAMDAO' => 'Huyện Tam Đảo',
                'VINHPHUCKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'QUANGNINH' => [
            'name' => 'Quảng Ninh',
            'cities' => [
                'THANHPHOHALONG' => 'Thành phố Hạ Long',
                'THANHPHOCAMPHA' => 'Thành phố Cẩm Phả',
                'THANHPHOUONGBI' => 'Thành phố Uông Bí',
                'THANHPHOMONGCAI' => 'Thành phố Móng Cái',
                'HUYENBINHLIEU' => 'Huyện Bình Liêu',
                'HUYENDAMHA' => 'Huyện Đầm Hà',
                'HUYENHAIHA' => 'Huyện Hải Hà',
                'HUYENTIENYEN' => 'Huyện Tiên Yên',
                'HUYENBACHE' => 'Huyện Ba Chẽ',
                'HUYENDONGTRIEU' => 'Huyện Đông Triều',
                'THIXAQUANGYEN' => 'Thị xã Quảng Yên',
                'HUYENHOANHBO' => 'Huyện Hoành Bồ',
                'HUYENVANDON' => 'Huyện Vân Đồn',
                'HUYENCOTO' => 'Huyện Cô Tô',
                'QUANGNINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BACGIANG' => [
            'name' => 'Bắc Giang',
            'cities' => [
                'THANHPHOBACGIANG' => 'Thành phố Bắc Giang',
                'HUYENYENTHE' => 'Huyện Yên Thế',
                'HUYENLUCNGAN' => 'Huyện Lục Ngạn',
                'HUYENSONDONG' => 'Huyện Sơn Động',
                'HUYENLUCNAM' => 'Huyện Lục Nam',
                'HUYENTANYEN' => 'Huyện Tân Yên',
                'HUYENHIEPHOA' => 'Huyện Hiệp Hoà',
                'HUYENLANGGIANG' => 'Huyện Lạng Giang',
                'HUYENVIETYEN' => 'Huyện Việt Yên',
                'HUYENYENDUNG' => 'Huyện Yên Dũng',
                'BACGIANGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BACNINH' => [
            'name' => 'Bắc Ninh',
            'cities' => [
                'THANHPHOBACNINH' => 'Thành phố Bắc Ninh',
                'HUYENYENPHONG' => 'Huyện Yên Phong',
                'HUYENQUEVO' => 'Huyện Quế Võ',
                'HUYENTIENDU' => 'Huyện Tiên Du',
                'THIXATUSON' => 'Thị xã Từ Sơn',
                'HUYENTHUANTHANH' => 'Huyện Thuận Thành',
                'HUYENGIABINH' => 'Huyện Gia Bình',
                'HUYENLUONGTAI' => 'Huyện Lương Tài',
                'BACNINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'HAIDUONG' => [
            'name' => 'Hải Dương',
            'cities' => [
                'THANHPHOHAIDUONG' => 'Thành phố Hải Dương',
                'THIXACHILINH' => 'Thị xã Chí Linh',
                'HUYENNAMSACH' => 'Huyện Nam Sách',
                'HUYENKINHMON' => 'Huyện Kinh Môn',
                'HUYENGIALOC' => 'Huyện Gia Lộc',
                'HUYENTUKY' => 'Huyện Tứ Kỳ',
                'HUYENTHANHMIEN' => 'Huyện Thanh Miện',
                'HUYENNINHGIANG' => 'Huyện Ninh Giang',
                'HUYENCAMGIANG' => 'Huyện Cẩm Giàng',
                'HUYENTHANHHA' => 'Huyện Thanh Hà',
                'HUYENKIMTHANH' => 'Huyện Kim Thành',
                'HUYENBINHGIANG' => 'Huyện Bình Giang',
                'HAIDUONGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'HUNGYEN' => [
            'name' => 'Hưng Yên',
            'cities' => [
                'THANHPHOHUNGYEN' => 'Thành phố Hưng Yên',
                'HUYENKIMDONG' => 'Huyện Kim Động',
                'HUYENANTHI' => 'Huyện Ân Thi',
                'HUYENKHOAICHAU' => 'Huyện Khoái Châu',
                'HUYENYENMY' => 'Huyện Yên Mỹ',
                'HUYENTIENLU' => 'Huyện Tiên Lữ',
                'HUYENPHUCU' => 'Huyện Phù Cừ',
                'HUYENMYHAO' => 'Huyện Mỹ Hào',
                'HUYENVANLAM' => 'Huyện Văn Lâm',
                'HUYENVANGIANG' => 'Huyện Văn Giang',
                'HUNGYENKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'HOABINH' => [
            'name' => 'Hòa Bình',
            'cities' => [
                'THANHPHOHOABINH' => 'Thành phố Hoà Bình',
                'HUYENDABAC' => 'Huyện Đà Bắc',
                'HUYENMAICHAU' => 'Huyện Mai Châu',
                'HUYENTANLAC' => 'Huyện Tân Lạc',
                'HUYENLACSON' => 'Huyện Lạc Sơn',
                'HUYENKYSON' => 'Huyện Kỳ Sơn',
                'HUYENLU­ONGSON' => 'Huyện Lư­ơng Sơn',
                'HUYENKIMBOI' => 'Huyện Kim Bôi',
                'HUYENLACTHUY' => 'Huyện Lạc Thuỷ',
                'HUYENYENTHUY' => 'Huyện Yên Thuỷ',
                'HUYENCAOPHONG' => 'Huyện Cao Phong',
                'HOABINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'HANAM' => [
            'name' => 'Hà Nam',
            'cities' => [
                'THANHPHOPHULY' => 'Thành phố Phủ Lý',
                'HUYENDUYTIEN' => 'Huyện Duy Tiên',
                'HUYENKIMBANG' => 'Huyện Kim Bảng',
                'HUYENLYNHAN' => 'Huyện Lý Nhân',
                'HUYENTHANHLIEM' => 'Huyện Thanh Liêm',
                'HUYENBINHLUC' => 'Huyện Bình Lục',
                'HANAMKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'NAMDINH' => [
            'name' => 'Nam Định',
            'cities' => [
                'THANHPHONAMDINH' => 'Thành phố Nam Định',
                'HUYENMYLOC' => 'Huyện Mỹ Lộc',
                'HUYENXUANTRUONG' => 'Huyện Xuân Trường',
                'HUYENGIAOTHUY' => 'Huyện Giao Thủy',
                'HUYENYYEN' => 'Huyện Ý Yên',
                'HUYENVUBAN' => 'Huyện Vụ Bản',
                'HUYENNAMTRUC' => 'Huyện Nam Trực',
                'HUYENTRUCNINH' => 'Huyện Trực Ninh',
                'HUYENNGHIAHUNG' => 'Huyện Nghĩa Hưng',
                'HUYENHAIHAU' => 'Huyện Hải Hậu',
                'NAMDINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'THAIBINH' => [
            'name' => 'Thái Bình',
            'cities' => [
                'THANHPHOTHAIBINH' => 'Thành phố Thái Bình',
                'HUYENQUYNHPHU' => 'Huyện Quỳnh Phụ',
                'HUYENHUNGHA' => 'Huyện Hưng Hà',
                'HUYENDONGHUNG' => 'Huyện Đông Hưng',
                'HUYENVUTHU' => 'Huyện Vũ Thư',
                'HUYENKIENXUONG' => 'Huyện Kiến Xương',
                'HUYENTIENHAI' => 'Huyện Tiền Hải',
                'HUYENTHAITHUY' => 'Huyện Thái Thuỵ',
                'THAIBINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'NINHBINH' => [
            'name' => 'Ninh Bình',
            'cities' => [
                'THANHPHONINHBINH' => 'Thành phố Ninh Bình',
                'THIXATAMDIEP' => 'Thị xã Tam Điệp',
                'HUYENNHOQUAN' => 'Huyện Nho Quan',
                'HUYENGIAVIEN' => 'Huyện Gia Viễn',
                'HUYENHOALU' => 'Huyện Hoa Lư',
                'HUYENYENMO' => 'Huyện Yên Mô',
                'HUYENKIMSON' => 'Huyện Kim Sơn',
                'HUYENYENKHANH' => 'Huyện Yên Khánh',
                'NINHBINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'THANHHOA' => [
            'name' => 'Thanh Hóa',
            'cities' => [
                'THANHPHOTHANHHOA' => 'Thành phố Thanh Hoá',
                'THIXABIMSON' => 'Thị xã Bỉm Sơn',
                'THIXASAMSON' => 'Thị xã Sầm Sơn',
                'HUYENQUANHOA' => 'Huyện Quan Hoá',
                'HUYENQUANSON' => 'Huyện Quan Sơn',
                'HUYENMUONGLAT' => 'Huyện Mường Lát',
                'HUYENBATHUOC' => 'Huyện Bá Thước',
                'HUYENTHUONGXUAN' => 'Huyện Thường Xuân',
                'HUYENNHUXUAN' => 'Huyện Như Xuân',
                'HUYENNHUTHANH' => 'Huyện Như Thanh',
                'HUYENLANGCHANH' => 'Huyện Lang Chánh',
                'HUYENNGOCLAC' => 'Huyện Ngọc Lặc',
                'HUYENTHACHTHANH' => 'Huyện Thạch Thành',
                'HUYENCAMTHUY' => 'Huyện Cẩm Thủy',
                'HUYENTHOXUAN' => 'Huyện Thọ Xuân',
                'HUYENVINHLOC' => 'Huyện Vĩnh Lộc',
                'HUYENTHIEUHOA' => 'Huyện Thiệu Hoá',
                'HUYENTRIEUSON' => 'Huyện Triệu Sơn',
                'HUYENNONGCONG' => 'Huyện Nông Cống',
                'HUYENDONGSON' => 'Huyện Đông Sơn',
                'HUYENHATRUNG' => 'Huyện Hà Trung',
                'HUYENHOANGHOA' => 'Huyện Hoằng Hoá',
                'HUYENNGASON' => 'Huyện Nga Sơn',
                'HUYENHAULOC' => 'Huyện Hậu Lộc',
                'HUYENQUANGXUONG' => 'Huyện Quảng Xương',
                'HUYENTINHGIA' => 'Huyện Tĩnh Gia',
                'HUYENYENDINH' => 'Huyện Yên Định',
                'THANHHOAKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'NGHEAN' => [
            'name' => 'Nghệ An',
            'cities' => [
                'THANHPHOVINH' => 'Thành phố Vinh',
                'THIXACUALO' => 'Thị xã Cửa Lò',
                'HUYENQUYCHAU' => 'Huyện Quỳ Châu',
                'HUYENQUYHOP' => 'Huyện Quỳ Hợp',
                'HUYENNGHIADAN' => 'Huyện Nghĩa Đàn',
                'HUYENQUYNHLUU' => 'Huyện Quỳnh Lưu',
                'HUYENKYSON' => 'Huyện Kỳ Sơn',
                'HUYENTUONGDUONG' => 'Huyện Tương Dương',
                'HUYENCONCUONG' => 'Huyện Con Cuông',
                'HUYENTANKY' => 'Huyện Tân Kỳ',
                'HUYENYENTHANH' => 'Huyện Yên Thành',
                'HUYENDIENCHAU' => 'Huyện Diễn Châu',
                'HUYENANHSON' => 'Huyện Anh Sơn',
                'HUYENDOLUONG' => 'Huyện Đô Lương',
                'HUYENTHANHCHUONG' => 'Huyện Thanh Chương',
                'HUYENNGHILOC' => 'Huyện Nghi Lộc',
                'HUYENNAMDAN' => 'Huyện Nam Đàn',
                'HUYENHUNGNGUYEN' => 'Huyện Hưng Nguyên',
                'HUYENQUEPHONG' => 'Huyện Quế Phong',
                'THIXATHAIHOA' => 'Thị Xã Thái Hòa',
                'THIXAHOANGMAI' => 'Thị Xã Hoàng Mai',
                'NGHEANKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'HATINH' => [
            'name' => 'Hà Tĩnh',
            'cities' => [
                'THANHPHOHATINH' => 'Thành phố Hà Tĩnh',
                'THIXAHONGLINH' => 'Thị xã Hồng Lĩnh',
                'HUYENHUONGSON' => 'Huyện Hương Sơn',
                'HUYENDUCTHO' => 'Huyện Đức Thọ',
                'HUYENNGHIXUAN' => 'Huyện Nghi Xuân',
                'HUYENCANLOC' => 'Huyện Can Lộc',
                'HUYENHUONGKHE' => 'Huyện Hương Khê',
                'HUYENTHACHHA' => 'Huyện Thạch Hà',
                'HUYENCAMXUYEN' => 'Huyện Cẩm Xuyên',
                'HUYENKYANH' => 'Huyện Kỳ Anh',
                'HUYENVUQUANG' => 'Huyện Vũ Quang',
                'HUYENLOCHA' => 'Huyện Lộc Hà',
                'HATINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'QUANGBINH' => [
            'name' => 'Quảng Bình',
            'cities' => [
                'THANHPHODONGHOI' => 'Thành phố Đồng Hới',
                'HUYENTUYENHOA' => 'Huyện Tuyên Hoá',
                'HUYENMINHHOA' => 'Huyện Minh Hoá',
                'HUYENQUANGTRACH' => 'Huyện Quảng Trạch',
                'HUYENBOTRACH' => 'Huyện Bố Trạch',
                'HUYENQUANGNINH' => 'Huyện Quảng Ninh',
                'HUYENLETHUY' => 'Huyện Lệ Thuỷ',
                'QUANGBINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'QUANGTRI' => [
            'name' => 'Quảng Trị',
            'cities' => [
                'THANHPHODONGHA' => 'Thành phố Đông Hà',
                'THIXAQUANGTRI' => 'Thị xã Quảng Trị',
                'HUYENVINHLINH' => 'Huyện Vĩnh Linh',
                'HUYENGIOLINH' => 'Huyện Gio Linh',
                'HUYENCAMLO' => 'Huyện Cam Lộ',
                'HUYENTRIEUPHONG' => 'Huyện Triệu Phong',
                'HUYENHAILANG' => 'Huyện Hải Lăng',
                'HUYENHUONGHOA' => 'Huyện Hướng Hóa',
                'HUYENDAKRONG' => 'Huyện Đăk Rông',
                'HUYENDAOCONCO' => 'Huyện đảo Cồn Cỏ',
                'QUANGTRIKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'THUATHIENHUE' => [
            'name' => 'Thừa Thiên Huế',
            'cities' => [
                'THANHPHOHUE' => 'Thành phố Huế',
                'HUYENPHONGDIEN' => 'Huyện Phong Điền',
                'HUYENQUANGDIEN' => 'Huyện Quảng Điền',
                'THIXAHUONGTRA' => 'Thị xã Hương Trà',
                'HUYENPHUVANG' => 'Huyện Phú Vang',
                'THIXAHUONGTHUY' => 'Thị xã Hương Thủy',
                'HUYENPHULOC' => 'Huyện Phú Lộc',
                'HUYENNAMDONG' => 'Huyện Nam Đông',
                'HUYENALUOI' => 'Huyện A Lưới',
                'THUATHIENHUEKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'QUANGNAM' => [
            'name' => 'Quảng Nam',
            'cities' => [
                'THANHPHOTAMKY' => 'Thành phố Tam Kỳ',
                'THANHPHOHOIAN' => 'Thành phố Hội An',
                'HUYENDUYXUYEN' => 'Huyện Duy Xuyên',
                'HUYENDIENBAN' => 'Huyện Điện Bàn',
                'HUYENDAILOC' => 'Huyện Đại Lộc',
                'HUYENQUESON' => 'Huyện Quế Sơn',
                'HUYENHIEPDUC' => 'Huyện Hiệp Đức',
                'HUYENTHANGBINH' => 'Huyện Thăng Bình',
                'HUYENNUITHANH' => 'Huyện Núi Thành',
                'HUYENTIENPHUOC' => 'Huyện Tiên Phước',
                'HUYENBACTRAMY' => 'Huyện Bắc Trà My',
                'HUYENDONGGIANG' => 'Huyện Đông Giang',
                'HUYENNAMGIANG' => 'Huyện Nam Giang',
                'HUYENPHUOCSON' => 'Huyện Phước Sơn',
                'HUYENNAMTRAMY' => 'Huyện Nam Trà My',
                'HUYENTAYGIANG' => 'Huyện Tây Giang',
                'HUYENPHUNINH' => 'Huyện Phú Ninh',
                'HUYENNONGSON' => 'Huyện Nông Sơn',
                'QUANGNAMKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'QUANGNGAI' => [
            'name' => 'Quảng Ngãi',
            'cities' => [
                'THANHPHOQUANGNGAI' => 'Thành phố Quảng Ngãi',
                'HUYENLYSON' => 'Huyện Lý Sơn',
                'HUYENBINHSON' => 'Huyện Bình Sơn',
                'HUYENTRABONG' => 'Huyện Trà Bồng',
                'HUYENSONTINH' => 'Huyện Sơn Tịnh',
                'HUYENSONHA' => 'Huyện Sơn Hà',
                'HUYENTUNGHIA' => 'Huyện Tư Nghĩa',
                'HUYENNGHIAHANH' => 'Huyện Nghĩa Hành',
                'HUYENMINHLONG' => 'Huyện Minh Long',
                'HUYENMODUC' => 'Huyện Mộ Đức',
                'HUYENDUCPHO' => 'Huyện Đức Phổ',
                'HUYENBATO' => 'Huyện Ba Tơ',
                'HUYENSONTAY' => 'Huyện Sơn Tây',
                'HUYENTAYTRA' => 'Huyện Tây Trà',
                'QUANGNGAIKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'KONTUM' => [
            'name' => 'Kon Tum',
            'cities' => [
                'THANHPHOKONTUM' => 'Thành phố Kon Tum',
                'HUYENDAKGLEI' => 'Huyện Đăk Glei',
                'HUYENNGOCHOI' => 'Huyện Ngọc Hồi',
                'HUYENDAKTO' => 'Huyện Đăk Tô',
                'HUYENSATHAY' => 'Huyện Sa Thầy',
                'HUYENKONPLONG' => 'Huyện Kon Plông',
                'HUYENDAKHA' => 'Huyện Đăk Hà',
                'HUYENKONRAY' => 'Huyện Kon Rẫy',
                'HUYENTUMORONG' => 'Huyện Tu Mơ Rông',
                'KONTUMKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BINHDINH' => [
            'name' => 'Bình Định',
            'cities' => [
                'THANHPHOQUYNHON' => 'Thành phố Quy Nhơn',
                'HUYENANLAO' => 'Huyện An Lão',
                'HUYENHOAIAN' => 'Huyện Hoài Ân',
                'HUYENHOAINHON' => 'Huyện Hoài Nhơn',
                'HUYENPHUMY' => 'Huyện Phù Mỹ',
                'HUYENPHUCAT' => 'Huyện Phù Cát',
                'HUYENVINHTHANH' => 'Huyện Vĩnh Thạnh',
                'HUYENTAYSON' => 'Huyện Tây Sơn',
                'HUYENVANCANH' => 'Huyện Vân Canh',
                'THIXAANNHON' => 'Thị xã An Nhơn',
                'HUYENTUYPHUOC' => 'Huyện Tuy Phước',
                'BINHDINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'GIALAI' => [
            'name' => 'Gia Lai',
            'cities' => [
                'THANHPHOPLEIKU' => 'Thành phố Pleiku',
                'HUYENCHUPAH' => 'Huyện Chư Păh',
                'HUYENMANGYANG' => 'Huyện Mang Yang',
                'HUYENKBANG' => 'Huyện Kbang',
                'THIXAANKHE' => 'Thị xã An Khê',
                'HUYENKONGCHRO' => 'Huyện Kông Chro',
                'HUYENDUCCO' => 'Huyện Đức Cơ',
                'HUYENCHUPRONG' => 'Huyện Chư Prông',
                'HUYENCHUSE' => 'Huyện Chư Sê',
                'THIXAAYUNPA' => 'Thị xã Ayun Pa',
                'HUYENKRONGPA' => 'Huyện Krông Pa',
                'HUYENIAGRAI' => 'Huyện Ia Grai',
                'HUYENDAKDOA' => 'Huyện Đak Đoa',
                'HUYENIAPA' => 'Huyện Ia Pa',
                'HUYENDAKPO' => 'Huyện Đak Pơ',
                'HUYENPHUTHIEN' => 'Huyện Phú Thiện',
                'HUYENCHUPUH' => 'Huyện Chư Pưh',
                'GIALAIKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'PHUYEN' => [
            'name' => 'Phú Yên',
            'cities' => [
                'THANHPHOTUYHOA' => 'Thành phố Tuy Hòa',
                'HUYENDONGXUAN' => 'Huyện Đồng Xuân',
                'THIXASONGCAU' => 'Thị Xã Sông Cầu',
                'HUYENTUYAN' => 'Huyện Tuy An',
                'HUYENSONHOA' => 'Huyện Sơn Hòa',
                'HUYENSONGHINH' => 'Huyện Sông Hinh',
                'HUYENDONGHOA' => 'Huyện Đông Hòa',
                'HUYENPHUHOA' => 'Huyện Phú Hòa',
                'HUYENTAYHOA' => 'Huyện Tây Hòa',
                'PHUYENKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'DAKLAK' => [
            'name' => 'Đăk Lăk',
            'cities' => [
                'THANHPHOBUONMATHUOT' => 'Thành phố Buôn Ma Thuột',
                'HUYENEAHLEO' => 'Huyện Ea H Leo',
                'HUYENKRONGBUK' => 'Huyện Krông Buk',
                'HUYENKRONGNANG' => 'Huyện Krông Năng',
                'HUYENEASUP' => 'Huyện Ea Súp',
                'HUYENCUMGAR' => 'Huyện Cư Mgar',
                'HUYENKRONGPAC' => 'Huyện Krông Pắc',
                'HUYENEAKAR' => 'Huyện Ea Kar',
                'HUYENMDRAK' => 'Huyện M Đrăk',
                'HUYENKRONGANA' => 'Huyện Krông Ana',
                'HUYENKRONGBONG' => 'Huyện Krông Bông',
                'HUYENLAK' => 'Huyện Lăk',
                'HUYENBUONDON' => 'Huyện Buôn Đôn',
                'HUYENCUKUIN' => 'Huyện Cư Kuin',
                'THIXABUONHO' => 'Thị Xã Buôn Hồ',
                'DAKLAKKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'KHANHHOA' => [
            'name' => 'Khánh Hòa',
            'cities' => [
                'THANHPHONHATRANG' => 'Thành phố Nha Trang',
                'HUYENVANNINH' => 'Huyện Vạn Ninh',
                'HUYENNINHHOA' => 'Huyện Ninh Hoà',
                'HUYENDIENKHANH' => 'Huyện Diên Khánh',
                'HUYENKHANHVINH' => 'Huyện Khánh Vĩnh',
                'THIXACAMRANH' => 'Thị xã Cam Ranh',
                'HUYENKHANHSON' => 'Huyện Khánh Sơn',
                'HUYENDAOTRUONGSA' => 'Huyện đảo Trường Sa',
                'HUYENCAMLAM' => 'Huyện Cam Lâm',
                'KHANHHOAKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'LAMDONG' => [
            'name' => 'Lâm Đồng',
            'cities' => [
                'THANHPHODALAT' => 'Thành phố Đà Lạt',
                'THANHPHOBAOLOC' => 'Thành phố Bảo Lộc',
                'HUYENDUCTRONG' => 'Huyện Đức Trọng',
                'HUYENDILINH' => 'Huyện Di Linh',
                'HUYENDONDUONG' => 'Huyện Đơn Dương',
                'HUYENLACDUONG' => 'Huyện Lạc Dương',
                'HUYENDAHUOAI' => 'Huyện Đạ Huoai',
                'HUYENDATEH' => 'Huyện Đạ Tẻh',
                'HUYENCATTIEN' => 'Huyện Cát Tiên',
                'HUYENLAMHA' => 'Huyện Lâm Hà',
                'HUYENBAOLAM' => 'Huyện Bảo Lâm',
                'HUYENDAMRONG' => 'Huyện Đam Rông',
                'LAMDONGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BINHPHUOC' => [
            'name' => 'Bình Phước',
            'cities' => [
                'THIXADONGXOAI' => 'Thị xã Đồng Xoài',
                'HUYENDONGPHU' => 'Huyện Đồng Phú',
                'HUYENCHONTHANH' => 'Huyện Chơn Thành',
                'HUYENBINHLONG' => 'Huyện Bình Long',
                'HUYENLOCNINH' => 'Huyện Lộc Ninh',
                'HUYENBUDOP' => 'Huyện Bù Đốp',
                'HUYENPHUOCLONG' => 'Huyện Phước Long',
                'HUYENBUDANG' => 'Huyện Bù Đăng',
                'HUYENHONQUAN' => 'Huyện Hớn Quản',
                'HUYENBUGIAMAP' => 'Huyện Bù Gia Mập',
                'BINHPHUOCKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BINHDUONG' => [
            'name' => 'Bình Dương',
            'cities' => [
                'THPHOTHUDAUMOT' => 'Th. phố Thủ Dầu Một',
                'HUYENBENCAT' => 'Huyện Bến Cát',
                'HUYENTANUYEN' => 'Huyện Tân Uyên',
                'THIXATHUANAN' => 'Thị xã Thuận An',
                'THIXADIAN' => 'Thị xã Dĩ An',
                'HUYENPHUGIAO' => 'Huyện Phú Giáo',
                'HUYENDAUTIENG' => 'Huyện Dầu Tiếng',
                'BINHDUONGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'NINHTHUAN' => [
            'name' => 'Ninh Thuận',
            'cities' => [
                'THANHPHOPHANRANGTHAPCHAM' => 'Thành phố Phan Rang -Tháp Chàm',
                'HUYENNINHSON' => 'Huyện Ninh Sơn',
                'HUYENNINHHAI' => 'Huyện Ninh Hải',
                'HUYENNINHPHUOC' => 'Huyện Ninh Phước',
                'HUYENBACAI' => 'Huyện Bác Ái',
                'HUYENTHUANBAC' => 'Huyện Thuận Bắc',
                'HUYENTHUANNAM' => 'Huyện Thuận Nam',
                'NINHTHUANKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'TAYNINH' => [
            'name' => 'Tây Ninh',
            'cities' => [
                'THIXATAYNINH' => 'Thị xã Tây Ninh',
                'HUYENTANBIEN' => 'Huyện Tân Biên',
                'HUYENTANCHAU' => 'Huyện Tân Châu',
                'HUYENDUONGMINHCHAU' => 'Huyện Dương Minh Châu',
                'HUYENCHAUTHANH' => 'Huyện Châu Thành',
                'HUYENHOATHANH' => 'Huyện Hòa Thành',
                'HUYENBENCAU' => 'Huyện Bến Cầu',
                'HUYENGODAU' => 'Huyện Gò Dầu',
                'HUYENTRANGBANG' => 'Huyện Trảng Bàng',
                'TAYNINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BINHTHUAN' => [
            'name' => 'Bình Thuận',
            'cities' => [
                'THANHPHOPHANTHIET' => 'Thành phố Phan Thiết',
                'HUYENTUYPHONG' => 'Huyện Tuy Phong',
                'HUYENBACBINH' => 'Huyện Bắc Bình',
                'HUYENHAMTHUANBAC' => 'Huyện Hàm Thuận Bắc',
                'HUYENHAMTHUANNAM' => 'Huyện Hàm Thuận Nam',
                'HUYENHAMTAN' => 'Huyện Hàm Tân',
                'HUYENDUCLINH' => 'Huyện Đức Linh',
                'HUYENTANHLINH' => 'Huyện Tánh Linh',
                'HUYENDAOPHUQUY' => 'Huyện đảo Phú Quý',
                'THIXALAGI' => 'Thị xã La Gi',
                'BINHTHUANKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'DONGNAI' => [
            'name' => 'Đồng Nai',
            'cities' => [
                'THANHPHOBIENHOA' => 'Thành phố Biên Hoà',
                'HUYENVINHCUU' => 'Huyện Vĩnh Cửu',
                'HUYENTANPHU' => 'Huyện Tân Phú',
                'HUYENDINHQUAN' => 'Huyện Định Quán',
                'HUYENTHONGNHAT' => 'Huyện Thống Nhất',
                'THIXALONGKHANH' => 'Thị xã Long Khánh',
                'HUYENXUANLOC' => 'Huyện Xuân Lộc',
                'HUYENLONGTHANH' => 'Huyện Long Thành',
                'HUYENNHONTRACH' => 'Huyện Nhơn Trạch',
                'HUYENTRANGBOM' => 'Huyện Trảng Bom',
                'HUYENCAMMY' => 'Huyện Cẩm Mỹ',
                'DONGNAIKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'LONGAN' => [
            'name' => 'Long An',
            'cities' => [
                'THANHPHOTANAN' => 'Thành phố Tân An',
                'HUYENVINHHUNG' => 'Huyện Vĩnh Hưng',
                'HUYENMOCHOA' => 'Huyện Mộc Hoá',
                'HUYENTANTHANH' => 'Huyện Tân Thạnh',
                'HUYENTHANHHOA' => 'Huyện Thạnh Hoá',
                'HUYENDUCHUE' => 'Huyện Đức Huệ',
                'HUYENDUCHOA' => 'Huyện Đức Hoà',
                'HUYENBENLUC' => 'Huyện Bến Lức',
                'HUYENTHUTHUA' => 'Huyện Thủ Thừa',
                'HUYENCHAUTHANH' => 'Huyện Châu Thành',
                'HUYENTANTRU' => 'Huyện Tân Trụ',
                'HUYENCANDUOC' => 'Huyện Cần Đước',
                'HUYENCANGIUOC' => 'Huyện Cần Giuộc',
                'HUYENTANHUNG' => 'Huyện Tân Hưng',
                'THIXAKIENTUONG' => 'Thị xã Kiến Tường',
                'LONGANKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'DONGTHAP' => [
            'name' => 'Đồng Tháp',
            'cities' => [
                'THANHPHOCAOLANH' => 'Thành phố Cao Lãnh',
                'THANHPHOSADEC' => 'Thành phố Sa Đéc',
                'HUYENTANHONG' => 'Huyện Tân Hồng',
                'HUYENHONGNGU' => 'Huyện Hồng Ngự',
                'HUYENTAMNONG' => 'Huyện Tam Nông',
                'HUYENTHANHBINH' => 'Huyện Thanh Bình',
                'HUYENCAOLANH' => 'Huyện Cao Lãnh',
                'HUYENLAPVO' => 'Huyện Lấp Vò',
                'HUYENTHAPMUOI' => 'Huyện Tháp Mười',
                'HUYENLAIVUNG' => 'Huyện Lai Vung',
                'HUYENCHAUTHANH' => 'Huyện Châu Thành',
                'THIXAHONGNGU' => 'Thị Xã Hồng Ngự',
                'DONGTHAPKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'ANGIANG' => [
            'name' => 'An Giang',
            'cities' => [
                'THANHPHOLONGXUYEN' => 'Thành phố Long Xuyên',
                'THIXACHAUDOC' => 'Thị xã Châu Đốc',
                'HUYENANPHU' => 'Huyện An Phú',
                'HUYENTANCHAU' => 'Huyện Tân Châu',
                'HUYENPHUTAN' => 'Huyện Phú Tân',
                'HUYENTINHBIEN' => 'Huyện Tịnh Biên',
                'HUYENTRITON' => 'Huyện Tri Tôn',
                'HUYENCHAUPHU' => 'Huyện Châu Phú',
                'HUYENCHOMOI' => 'Huyện Chợ Mới',
                'HUYENCHAUTHANH' => 'Huyện Châu Thành',
                'HUYENTHOAISON' => 'Huyện Thoại Sơn',
                'ANGIANGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BARIAVUNGTAU' => [
            'name' => 'Bà Rịa Vũng Tàu',
            'cities' => [
                'THANHPHOVUNGTAU' => 'Thành phố Vũng Tàu',
                'THANHPHOBARIA' => 'Thành phố Bà Rịa',
                'HUYENXUYENMOC' => 'Huyện Xuyên Mộc',
                'HUYENLONGDIEN' => 'Huyện Long Điền',
                'HUYENCONDAO' => 'Huyện Côn Đảo',
                'HUYENTANTHANH' => 'Huyện Tân Thành',
                'HUYENCHAUDUC' => 'Huyện Châu Đức',
                'HUYENDATDO' => 'Huyện Đất Đỏ',
                'BARIAVUNGTAUKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'TIENGIANG' => [
            'name' => 'Tiền Giang',
            'cities' => [
                'THANHPHOMYTHO' => 'Thành phố Mỹ Tho',
                'THIXAGOCONG' => 'Thị xã Gò Công',
                'HUYENCAIBE' => 'Huyện Cái bè',
                'HUYENCAILAY' => 'Huyện Cai lậy',
                'HUYENCHAUTHANH' => 'Huyện Châu thành',
                'HUYENCHOGAO' => 'Huyện Chợ Gạo',
                'HUYENGOCONGTAY' => 'Huyện Gò Công Tây',
                'HUYENGOCONGDONG' => 'Huyện Gò Công Đông',
                'HUYENTANPHUOC' => 'Huyện Tân Phước',
                'HUYENTANPHUDONG' => 'Huyện Tân Phú Đông',
                'TIENGIANGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'KIENGIANG' => [
            'name' => 'Kiên Giang',
            'cities' => [
                'THANHPHORACHGIA' => 'Thành phố Rạch Giá',
                'THIXAHATIEN' => 'Thị xã Hà Tiên',
                'HUYENKIENLUONG' => 'Huyện Kiên Lương',
                'HUYENHONDAT' => 'Huyện Hòn Đất',
                'HUYENTANHIEP' => 'Huyện Tân Hiệp',
                'HUYENCHAUTHANH' => 'Huyện Châu Thành',
                'HUYENGIONGRIENG' => 'Huyện Giồng Riềng',
                'HUYENGOQUAO' => 'Huyện Gò Quao',
                'HUYENANBIEN' => 'Huyện An Biên',
                'HUYENANMINH' => 'Huyện An Minh',
                'HUYENVINHTHUAN' => 'Huyện Vĩnh Thuận',
                'HUYENPHUQUOC' => 'Huyện Phú Quốc',
                'HUYENKIENHAI' => 'Huyện Kiên Hải',
                'HUYENUMINHTHUONG' => 'Huyện U Minh Thượng',
                'HUYENGIANGTHANH' => 'Huyện Giang Thành',
                'KIENGIANGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'CANTHO' => [
            'name' => 'Cần Thơ',
            'cities' => [
                'QUANNINHKIEU' => 'Quận Ninh Kiều',
                'QUANBINHTHUY' => 'Quận Bình Thuỷ',
                'QUANCAIRANG' => 'Quận Cái Răng',
                'QUANOMON' => 'Quận Ô Môn',
                'HUYENPHONGDIEN' => 'Huyện Phong Điền',
                'HUYENCODO' => 'Huyện Cờ Đỏ',
                'HUYENVINHTHANH' => 'Huyện Vĩnh Thạnh',
                'QUANTHOTNOT' => 'Quận Thốt Nốt',
                'HUYENTHOILAI' => 'Huyện Thới Lai',
                'TRAVINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BENTRE' => [
            'name' => 'Bến Tre',
            'cities' => [
                'THANHPHOBENTRE' => 'Thành phố Bến Tre',
                'HUYENCHAUTHANH' => 'Huyện Châu Thành',
                'HUYENCHOLACH' => 'Huyện Chợ Lách',
                'HUYENMOCAYBAC' => 'Huyện Mỏ Cày Bắc',
                'HUYENGIONGTROM' => 'Huyện Giồng Trôm',
                'HUYENBINHDAI' => 'Huyện Bình Đại',
                'HUYENBATRI' => 'Huyện Ba Tri',
                'HUYENTHANHPHU' => 'Huyện Thạnh Phú',
                'HUYENMOCAYNAM' => 'Huyện Mỏ Cày Nam',
                'BENTREKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'VINHLONG' => [
            'name' => 'Vĩnh Long',
            'cities' => [
                'THANHPHOVINHLONG' => 'Thành phố Vĩnh Long',
                'HUYENLONGHO' => 'Huyện Long Hồ',
                'HUYENMANGTHIT' => 'Huyện Mang Thít',
                'THIXABINHMINH' => 'Thị xã Bình Minh',
                'HUYENTAMBINH' => 'Huyện Tam Bình',
                'HUYENTRAON' => 'Huyện Trà Ôn',
                'HUYENVUNGLIEM' => 'Huyện Vũng Liêm',
                'HUYENBINHTAN' => 'Huyện Bình Tân',
                'VINHLONGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'TRAVINH' => [
            'name' => 'Trà Vinh',
            'cities' => [
                'THANHPHOTRAVINH' => 'Thành phố Trà Vinh',
                'HUYENCANGLONG' => 'Huyện Càng Long',
                'HUYENCAUKE' => 'Huyện Cầu Kè',
                'HUYENTIEUCAN' => 'Huyện Tiểu Cần',
                'HUYENCHAUTHANH' => 'Huyện Châu Thành',
                'HUYENTRACU' => 'Huyện Trà Cú',
                'HUYENCAUNGANG' => 'Huyện Cầu Ngang',
                'HUYENDUYENHAI' => 'Huyện Duyên Hải',
                'TRAVINHKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'SOCTRANG' => [
            'name' => 'Sóc Trăng',
            'cities' => [
                'THANHPHOSOCTRANG' => 'Thành phố Sóc Trăng',
                'HUYENKESACH' => 'Huyện Kế Sách',
                'HUYENMYTU' => 'Huyện Mỹ Tú',
                'HUYENMYXUYEN' => 'Huyện Mỹ Xuyên',
                'HUYENTHANHTRI' => 'Huyện Thạnh Trị',
                'HUYENLONGPHU' => 'Huyện Long Phú',
                'THIXAVINHCHAU' => 'Thị xã Vĩnh Châu',
                'HUYENCULAODUNG' => 'Huyện Cù Lao Dung',
                'HUYENNGANAM' => 'Huyện Ngã Năm',
                'HUYENCHAUTHANH' => 'Huyện Châu Thành',
                'HUYENTRANDE' => 'Huyện Trần Đề',
                'SOCTRANGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'BACLIEU' => [
            'name' => 'Bạc Liêu',
            'cities' => [
                'THANHPHOBACLIEU' => 'Thành phố Bạc Liêu',
                'HUYENVINHLOI' => 'Huyện Vĩnh Lợi',
                'HUYENHONGDAN' => 'Huyện Hồng Dân',
                'HUYENGIARAI' => 'Huyện Giá Rai',
                'HUYENPHUOCLONG' => 'Huyện Phước Long',
                'HUYENDONGHAI' => 'Huyện Đông Hải',
                'HUYENHOABINH' => 'Huyện Hoà Bình',
                'BACLIEUKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'CAMAU' => [
            'name' => 'Cà Mau',
            'cities' => [
                'THANHPHOCAMAU' => 'Thành phố Cà Mau',
                'HUYENTHOIBINH' => 'Huyện Thới Bình',
                'HUYENUMINH' => 'Huyện U Minh',
                'HUYENTRANVANTHOI' => 'Huyện Trần Văn Thời',
                'HUYENCAINUOC' => 'Huyện Cái Nước',
                'HUYENDAMDOI' => 'Huyện Đầm Dơi',
                'HUYENNGOCHIEN' => 'Huyện Ngọc Hiển',
                'HUYENNAMCAN' => 'Huyện Năm Căn',
                'HUYENPHUTAN' => 'Huyện Phú Tân',
                'CAMAUKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'DIENBIEN' => [
            'name' => 'Điện Biên',
            'cities' => [
                'TP.DIENBIENPHU' => 'TP. Điện Biên Phủ',
                'THIXAMUONGLAY' => 'Thị xã Mường Lay',
                'HUYENDIENBIEN' => 'Huyện Điện Biên',
                'HUYENTUANGIAO' => 'Huyện Tuần Giáo',
                'HUYENMUONGCHA' => 'Huyện Mường Chà',
                'HUYENTUACHUA' => 'Huyện Tủa Chùa',
                'HUYENDIENBIENDONG' => 'Huyện Điện Biên Đông',
                'HUYENMUONGNHE' => 'Huyện Mường Nhé',
                'HUYENMUONGANG' => 'Huyện Mường Ảng',
                'DIENBIENKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'DAKNONG' => [
            'name' => 'Đắk Nông',
            'cities' => [
                'THIXAGIANGHIA' => 'Thị xã Gia Nghĩa',
                'HUYENDAKRLAP' => 'Huyện Đăk RLấp',
                'HUYENDAKMIL' => 'Huyện Đăk Mil',
                'HUYENCUJUT' => 'Huyện Cư Jút',
                'HUYENDAKSONG' => 'Huyện Đăk Song',
                'HUYENKRONGNO' => 'Huyện Krông Nô',
                'HUYENDAKGLONG' => 'Huyện Đăk GLong',
                'HUYENTUYDUC' => 'Huyện Tuy Đức',
                'DAKNONGKHAC' => 'Quận/Huyện khác',
            ]
        ],
        'HAUGIANG' => [
            'name' => 'Hậu Giang',
            'cities' => [
                'THANHPHOVITHANH' => 'Thành phố Vị Thanh',
                'HUYENVITHUY' => 'Huyện Vị Thuỷ',
                'HUYENLONGMY' => 'Huyện Long Mỹ',
                'HUYENPHUNGHIEP' => 'Huyện Phụng Hiệp',
                'HUYENCHAUTHANH' => 'Huyện Châu Thành',
                'HUYENCHAUTHANHA' => 'Huyện Châu Thành A',
                'THIXANGABAY' => 'Thị xã Ngã Bảy',
                'HAUGIANGKHAC' => 'Quận/Huyện khác',
            ],
        ]
    ];

    public static function getCodeFromName($name, $area = 'province')
    {
        foreach(self::$provinces as $code => $data)
        {
            if($area == 'province')
            {
                if($name == $data['name'])
                    return $code;
            }
            else
            {
                foreach($data['cities'] as $districtCode => $district)
                {
                    if(is_array($district))
                    {
                        if($name == $district['name'])
                            return $districtCode;
                    }
                    else if($name == $district)
                        return $districtCode;
                }
            }
        }

        return null;
    }
}