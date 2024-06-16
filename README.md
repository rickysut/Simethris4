<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## How to install

1. Copy repository
2. Copy .env_sample to .env and setup your db
3. Composer install
4. php artisan migrate:fresh --seed
5. npm install
6. npm run dev
7. php artisan serve
8. run php artisan schedule:test, select 0 then.

## Existing Version

1. addLokasi dan editLokasi.blade pembatasan input max luas lahan di remark
2. data step menjadi 3 digit
3. field anggota_id di db master_anggotas menjadi not unique
4. field luas_lahan di db data_realisasi menjadi double
5. menambahkan catch error message untuk debug di PullRiphController
6. menambahkan catch error message untuk debug di LoginController
7. reduce data load on initmap (attempt: 1)
8. count and sum method change to DataRealisasi instead of Lokasi
9. New Template SKL Heading


## Current Dev
1. Reduce data load on initmap (attempt: 2)
2. Give Administrator ability to reject the SKL Approval Submission.
3. Give user ability to change the data after Approval rejection.
4. Add new fields for Administrator to report where the product shall be go (sales in weight, keep in weight) in SKL Approval form.
5. Advance. Change user behavior from map drawing and or uploading the KML. Now the location are system provided.
   a. this will need new modules and database table
   b. new crud
6. New Modules
   a. Master Spatial Data incl. CRUD and
   b. Master CPCL data incl. CRUD
   c. Master Poktan data incl. CRUD
7. User able to re-submit their rejected submission by di Verificator or Administrator after they fullfill the requirements. The histories of the submmision are now available for each Commitment ID.
8. To prevent the the data faulty, the tables are seperated from the previous version.
9. Flagging Enhancement for some data. makes it easier to determined the statuses of the data.
10. Android Beta version for development preview are now available.
11. Product Distribution features are now available. This features were added to determined the final distribution of the product (garlic): Market Release or stored as seeds.
