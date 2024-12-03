<template>
    <footer class="bg-gradient-to-b from-[#183318] to-[#334930] text-white mt-auto">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-18">
            <!-- Grid principal con dos filas -->
            <div class="flex flex-col space-y-3">
                <!-- Fila superior: Mapa -->
                <div class="flex flex-col items-center">
                    <h3 class="text-sm font-montserrat font-semibold">Ubicación</h3>
                    <p class="text-xs text-[#ECF39E]/80 mb-1">Calle 51 #45-93, Medellín</p>
                    <div class="rounded-lg overflow-hidden shadow-lg h-28 w-full max-w-xl mx-auto">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.338260464583!2d-75.5858035878229!3d6.219047665287035!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e44293f0e114eef%3A0x99610cdd44c7c081!2sRiwi!5e0!3m2!1ses-419!2sco!4v1733186283093!5m2!1ses-419!2sco"
                            class="w-full h-full"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                </div>

                <!-- Línea divisoria -->
                <div class="border-t border-[#ECF39E]/10"></div>

                <!-- Fila inferior: Enlaces y GitHub -->
                <div class="grid grid-cols-3 gap-4 items-center justify-items-center py-2">
                    <!-- Legal -->
                    <div class="flex flex-col items-center">
                        <a href="/" class="text-xs hover:text-[#ECF39E] transition-colors duration-300 flex items-center">
                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Legal
                        </a>
                    </div>

                    <!-- Ayuda -->
                    <div class="flex flex-col items-center">
                        <a href="/" class="text-xs hover:text-[#ECF39E] transition-colors duration-300 flex items-center">
                            <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Ayuda
                        </a>
                    </div>

                    <!-- GitHub -->
                    <div class="flex flex-col items-center">
                        <a href="https://github.com/Crudzaso/la_rifa_mas_montanera"
                           target="_blank"
                           class="text-xs hover:text-[#ECF39E] transition-all duration-300 flex items-center">
                            <svg class="h-3 w-3 mr-1" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/>
                            </svg>
                            GitHub
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</template>
