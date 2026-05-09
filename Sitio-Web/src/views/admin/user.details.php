<section class="bg-base-100 rounded-3xl product">
    <div class="w-auto mx-auto bg-base-100 flex flex-col lg:flex-row justify-between 
            gap-12 lg:gap-20 p-8 product-container">

        <div class="flex items-center justify-center 
                lg:w-[50%] w-fit h-fit mx-auto lg:mx-0
                rounded-3xl bg-white figure-shadow p-12">
            
            <div class="text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-48 h-48 text-teal-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
        </div>

        <div class="w-[320px] flex flex-col justify-center gap-8 lg:w-2/3 w-full">
            <h2 class="text-3xl font-bold text-teal-950"><?=$user->user?></h2>
            
            <div class="flex flex-col gap-4">
                <div>
                    <p class="text-sm text-teal-700 font-semibold">Email:</p>
                    <p class="text-lg text-teal-950"><?=$user->email?></p>
                </div>

                <?php if(isset($user->rol)) : ?>
                <div>
                    <p class="text-sm text-teal-700 font-semibold">Rol:</p>
                    <p class="text-lg text-teal-950"><?=ucfirst($user->rol)?></p>
                </div>
                <?php endif; ?>

                <?php if(isset($user->description)) : ?>
                <div>
                    <p class="text-sm text-teal-700 font-semibold">Descripción:</p>
                    <p class="text-lg text-teal-950"><?=ucfirst($user->description)?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

     <a class="action-button" style="margin: auto;" href="<?=BASE_PATH?>/admin/users">
        Volver a Usuarios
    </a>
</section>