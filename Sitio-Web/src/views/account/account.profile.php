<section class="bg-base-100 rounded-3xl product" style="margin-top: 2rem; margin-bottom: 2rem;">
    <div class="w-auto mx-auto bg-base-100 flex flex-col lg:flex-row justify-between gap-12 lg:gap-20 p-16 product-container">

        <div class="flex items-center justify-center lg:w-[50%] w-fit h-fit mx-auto lg:mx-0 rounded-3xl bg-white figure-shadow p-12">
            <div class="text-center">
                <svg xmlns="www.w3.org/2000/svg" class="w-48 h-48 text-teal-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="w-full flex flex-col justify-center gap-8 lg:w-2/3">
            <h2 class="text-3xl font-bold text-teal-950">Mi Perfil</h2>
            
            <div class="flex flex-col gap-4">
                <div>
                    <p class="text-sm text-teal-700 font-semibold">Nombre de usuario:</p>
                    <p class="text-lg text-teal-950"><?= htmlspecialchars($user->username) ?></p>
                </div>

                <div>
                    <p class="text-sm text-teal-700 font-semibold">Correo electrónico:</p>
                    <p class="text-lg text-teal-950"><?= htmlspecialchars($user->email) ?></p>
                </div>

                <div>
                    <p class="text-sm text-teal-700 font-semibold">Rol en el sistema:</p>
                    <p class="text-lg text-teal-950"><span class="badge badge-ghost"><?= ucfirst(htmlspecialchars($user->role)) ?></span></p>
                </div>

                <?php if(!empty($user->description)) : ?>
                <div>
                    <p class="text-sm text-teal-700 font-semibold">Descripción:</p>
                    <p class="text-lg text-teal-950"><?= htmlspecialchars($user->description) ?></p>
                </div>
                <?php endif; ?>
            </div>

            <div class="operation-container" style="margin-top: 1rem; width:fit-content; display: flex; gap: 1rem; flex-direction: column;">
                <a href="<?= BASE_PATH ?>/" class="action-button" style="text-decoration: none;">
                    Volver al inicio
                </a>
                
                <a href="<?= BASE_PATH ?>/logout" class="delete-button" style="text-decoration: none;">
                    Cerrar sesión
                </a>
            </div>
        </div>
    </div>
</section>