<script>
    // document.addEventListener('click', function (event) {
    //     // alert('hii');
    //     // Check if the clicked element is an IMG inside any .imgs wrapper
    //     if (event.target.tagName === 'IMG' && event.target.closest('.imgs')) {
    //         const imageSrc = event.target.src;    
            
    //         let popover = document.getElementById('myimgpopover');
    //         // let popoverImg =popover.querySelector('img').src = imageSrc;            
            
    //         popover.style.backgroundImage = `url(${imageSrc})`;
    //         popover.style.backgroundPosition = 'center center';
    //         popover.style.backgroundRepeat = 'no-repeat';
    //         popover.style.backgroundSize = 'contain';
    //         popover.style.display = 'flex';
    //     }
    // });
    document.querySelector('.close-popup').addEventListener('click', function (){        
        document.querySelector('#myimgpopover').style.display = 'none !important';
    })
    // function previewKLDImages(input) {
    //     alert('hii kld');
    //     const files = input.files;
    //     const previewContainer = document.querySelector('#kld_images_preview');
    //     const previewContainer2 = document.querySelector('#kld_preview');
    //     // previewContainer.innerHTML = ''; // Clear previous previews        
    //     // previewContainer2.innerHTML = ''; // Clear previous previews        

    //     Array.from(files).forEach(file => {
    //         const reader = new FileReader();

    //         reader.onload = function (e) {
    //             const anchor = document.createElement('a');
    //             anchor.href = e.target.result;                
    //             anchor.style.margin = '10px';

    //             const fileType = file.type; // e.g., 'image/png', 'application/pdf'
    //             const fileExtension = file.name.split('.').pop().toLowerCase();


    //             if (fileType.startsWith('image/') || ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
    //                 anchor.setAttribute('data-fancybox', 'kld_gallery');

    //                 const img = document.createElement('img');
    //                 img.src = e.target.result;
    //                 img.alt = 'Preview Image';
    //                 img.style.width = '100px';
    //                 img.style.height = 'auto';
    //                 img.style.cursor = 'pointer';

    //                 anchor.appendChild(img);
    //             } 
    //             else if (fileType === 'application/pdf' || fileExtension === 'pdf') {
    //                 const objectUrl = URL.createObjectURL(file);
    //                 anchor.href = objectUrl;
    //                 anchor.target = '_blank';
    //                 anchor.textContent = file.name;
    //                 anchor.style.display = 'inline-block';
    //                 anchor.style.padding = '8px';
    //                 anchor.style.backgroundColor = '#f0f0f0';
    //                 anchor.style.border = '1px solid #ccc';
    //                 anchor.style.textDecoration = 'none';
    //                 anchor.style.color = '#333';

    //                 previewContainer.appendChild(anchor);
    //             }

    //             previewContainer.appendChild(anchor);                
    //         }
    //         reader.readAsDataURL(file);                        
    //     });
    // }

    // function previewKLDImages(input) {
    //     const files = input.files;

    //     Array.from(files).forEach((file, index) => {
    //         const reader = new FileReader();

    //         reader.onload = function (e) {
    //             const fileUrl = e.target.result;
    //             const fileExtension = file.name.split('.').pop().toLowerCase();
    //             const fileName = file.name;
    //             const tempId = Date.now() + '_' + index; // Temporary ID

    //             if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
    //                 $('#kld_images_preview').append(`
    //                     <div class="image-wrapper" style="position: relative; display: inline-block; margin-right: 5px;">
    //                         <span class="close-btn" data-table="job_kld_images" data-filename="${fileName}" data-id="${tempId}" 
    //                             onclick="this.parentElement.remove(); removeImage(this)" 
    //                             style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
    //                         <a href="${fileUrl}" data-fancybox="kld_gallery">
    //                             <img src="${fileUrl}" style="width:100px; height:auto; cursor:pointer;" alt="Mockup Image">
    //                         </a>
    //                     </div>
    //                 `);
    //             } 
    //             else if (fileExtension === 'pdf') {
    //                 $('#kld_preview').append(`
    //                     <div class="pdf-wrapper" style="position: relative; display: inline-block; margin-right: 5px; padding: 5px; border: 1px solid #ccc;">
    //                         <span class="close-btn" data-table="job_kld_images" data-filename="${fileName}" data-id="${tempId}" 
    //                             onclick="this.parentElement.remove(); removeImage(this)" 
    //                             style="position: absolute; top: -5px; right: -5px; background: red; color: white; border-radius: 50%; width: 18px; height: 18px; text-align: center; font-size: 14px; cursor: pointer;">&times;</span>
    //                         <a href="${fileUrl}" data-fancybox="kld_gallery" target="_blank" style="text-decoration: none; color: #007bff;">
    //                             ${fileName}
    //                         </a>
    //                     </div>
    //                 `);
    //             }
    //         }
    //         reader.readAsDataURL(file);                        
    //     });

    //     // // Reset input to allow reselecting same file
    //     // input.value = "";
    // }

    // function previewMockupImages(input) {
    //     const preview = document.querySelector('#mock_up_images_preview');
    //     const previewContainer2 = document.querySelector('#mock_up_preview');
    //     preview.innerHTML = '';
    //     previewContainer2.innerHTML = '';

    //     Array.from(input.files).forEach(file => {
    //         const ext = file.name.split('.').pop().toLowerCase();
    //         const url = URL.createObjectURL(file);
    //         const a = document.createElement('a');
    //         a.style.margin = '10px';

    //         if (file.type.startsWith('image/') || ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
    //             a.href = url;
    //             a.setAttribute('data-fancybox', 'mockup_gallery');

    //             const img = document.createElement('img');
    //             img.src = url;
    //             img.style.width = '100px';
    //             img.style.height = 'auto';
    //             img.style.cursor = 'pointer';

    //             a.appendChild(img);
    //         } else if (file.type === 'application/pdf' || ext === 'pdf') {
    //             a.href = url;
    //             a.target = '_blank';
    //             a.textContent = file.name;
    //             a.style.display = 'inline-block';
    //             a.style.padding = '6px';
    //             a.style.backgroundColor = '#f5f5f5';
    //             a.style.border = '1px solid #ccc';
    //             a.style.margin = '5px';
    //         }

    //         preview.appendChild(a);
    //     });
    // }

    // function previewApproveImages(input) {
    //     const preview = document.querySelector('#approve_images_preview');
    //     const previewContainer2 = document.querySelector('#approve_preview');
    //     preview.innerHTML = '';
    //     previewContainer2.innerHTML = '';

    //     Array.from(input.files).forEach(file => {
    //         const ext = file.name.split('.').pop().toLowerCase();
    //         const url = URL.createObjectURL(file);
    //         const a = document.createElement('a');
    //         a.style.margin = '10px';

    //         if (file.type.startsWith('image/') || ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
    //             a.href = url;
    //             a.setAttribute('data-fancybox', 'approve_gallery');

    //             const img = document.createElement('img');
    //             img.src = url;
    //             img.style.width = '100px';
    //             img.style.height = 'auto';
    //             img.style.cursor = 'pointer';

    //             a.appendChild(img);
    //         } else if (file.type === 'application/pdf' || ext === 'pdf') {
    //             a.href = url;
    //             a.target = '_blank';
    //             a.textContent = file.name;
    //             a.style.display = 'inline-block';
    //             a.style.padding = '6px';
    //             a.style.backgroundColor = '#f5f5f5';
    //             a.style.border = '1px solid #ccc';
    //             a.style.margin = '5px';
    //         }

    //         preview.appendChild(a);
    //     });
    // }

    // function previewSuppressionImages(input) {
    //     const preview = document.querySelector('#suppression_images_preview');
    //     const previewContainer2 = document.querySelector('#suppression_preview');
    //     preview.innerHTML = '';
    //     previewContainer2.innerHTML = '';

    //     Array.from(input.files).forEach(file => {
    //         const ext = file.name.split('.').pop().toLowerCase();
    //         const url = URL.createObjectURL(file);
    //         const a = document.createElement('a');
    //         a.style.margin = '10px';

    //         if (file.type.startsWith('image/') || ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
    //             a.href = url;
    //             a.setAttribute('data-fancybox', 'suppression_gallery');

    //             const img = document.createElement('img');
    //             img.src = url;
    //             img.style.width = '100px';
    //             img.style.height = 'auto';
    //             img.style.cursor = 'pointer';

    //             a.appendChild(img);
    //         } else if (file.type === 'application/pdf' || ext === 'pdf') {
    //             a.href = url;
    //             a.target = '_blank';
    //             a.textContent = file.name;
    //             a.style.display = 'inline-block';
    //             a.style.padding = '6px';
    //             a.style.backgroundColor = '#f5f5f5';
    //             a.style.border = '1px solid #ccc';
    //             a.style.margin = '5px';
    //         }

    //         preview.appendChild(a);
    //     });
    // }

    function previewImages(input, previewSelector, pdfPreviewSelector, galleryName) {
        const preview = document.querySelector(previewSelector);
        const pdfPreview = document.querySelector(pdfPreviewSelector);

        // Do NOT clear previews if you want to allow multiple selections
        // If you want to clear old ones on new select, uncomment:
        // preview.innerHTML = '';
        // pdfPreview.innerHTML = '';

        Array.from(input.files).forEach((file, index) => {
            const ext = file.name.split('.').pop().toLowerCase();
            const url = URL.createObjectURL(file);
            const tempId = Date.now() + '_' + index;

            // Wrapper
            const wrapper = document.createElement('div');
            wrapper.className = ext === 'pdf' ? 'pdf-wrapper' : 'image-wrapper';
            wrapper.style.position = 'relative';
            wrapper.style.display = 'inline-block';
            wrapper.style.marginRight = '5px';
            if (ext === 'pdf') {
                wrapper.style.padding = '5px';
                wrapper.style.border = '1px solid #ccc';
            }

            // Close button
            const closeBtn = document.createElement('span');
            closeBtn.className = 'close-btn';
            closeBtn.innerHTML = '&times;';
            closeBtn.dataset.id = tempId;
            closeBtn.dataset.filename = file.name;
            closeBtn.dataset.input = input.id; // which input this file belongs to
            closeBtn.style.position = 'absolute';
            closeBtn.style.top = '-5px';
            closeBtn.style.right = '-5px';
            closeBtn.style.background = 'red';
            closeBtn.style.color = 'white';
            closeBtn.style.borderRadius = '50%';
            closeBtn.style.width = '18px';
            closeBtn.style.height = '18px';
            closeBtn.style.textAlign = 'center';
            closeBtn.style.fontSize = '14px';
            closeBtn.style.cursor = 'pointer';
            closeBtn.onclick = function () {
                removeImage(this);
            };

            wrapper.appendChild(closeBtn);

            // File Preview
            const a = document.createElement('a');
            a.style.margin = '10px';

            if (file.type.startsWith('image/') || ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                a.href = url;
                a.setAttribute('data-fancybox', galleryName);

                const img = document.createElement('img');
                img.src = url;
                img.style.width = '100px';
                img.style.height = 'auto';
                img.style.cursor = 'pointer';

                a.appendChild(img);
                wrapper.appendChild(a);
                preview.appendChild(wrapper);
            } else if (file.type === 'application/pdf' || ext === 'pdf') {
                a.href = url;
                a.target = '_blank';
                a.textContent = file.name;
                a.style.display = 'inline-block';
                a.style.padding = '6px';
                a.style.backgroundColor = '#f5f5f5';
                a.style.border = '1px solid #ccc';
                a.style.margin = '5px';

                wrapper.appendChild(a);
                pdfPreview.appendChild(wrapper);
            }
        });
    }

    // Remove image from preview and input
    // function removeImage(elem) {
    //     const filename = elem.dataset.filename;
    //     const inputId = elem.dataset.input;
    //     const input = document.getElementById(inputId);
    //     const dt = new DataTransfer();

    //     // Rebuild FileList without the removed file
    //     Array.from(input.files).forEach(file => {
    //         if (file.name !== filename) {
    //             dt.items.add(file);
    //         }
    //     });

    //     input.files = dt.files;
    //     elem.parentElement.remove();

    //     console.log(`Removed file "${filename}" from input "${inputId}"`);
    // }

    // Specific functions for each type
    function previewKLDImages(input) {
        previewImages(input, '#kld_images_preview', '#kld_preview', 'kld_gallery');
    }
    function previewMockupImages(input) {
        previewImages(input, '#mock_up_images_preview', '#mock_up_preview', 'mockup_gallery');
    }
    function previewApproveImages(input) {
        previewImages(input, '#approve_images_preview', '#approve_preview', 'approve_gallery');
    }
    function previewSuppressionImages(input) {
        previewImages(input, '#suppression_images_preview', '#suppression_preview', 'suppression_gallery');
    }

    
   
</script>

<!-- Pickr CSS/JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/classic.min.css" />
<script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr"></script>

<script>
// Function to generate pentashade
function generateShades(hex) {
    function shade(hex, percent) {
        const f = parseInt(hex.slice(1), 16),
            t = percent < 0 ? 0 : 255,
            p = Math.abs(percent),
            R = f >> 16,
            G = f >> 8 & 0x00FF,
            B = f & 0x0000FF;
        return "#" + (
            0x1000000 +
            (Math.round((t - R) * p) + R) * 0x10000 +
            (Math.round((t - G) * p) + G) * 0x100 +
            (Math.round((t - B) * p) + B)
        ).toString(16).slice(1);
    }

    return [
        shade(hex, 0.4),
        shade(hex, 0.2),
        hex,
        shade(hex, -0.2),
        shade(hex, -0.4)
    ];
}

function hexToCmyk(hex) {
    hex = hex.replace(/^#/, '');

    let r = parseInt(hex.substr(0, 2), 16) / 255;
    let g = parseInt(hex.substr(2, 2), 16) / 255;
    let b = parseInt(hex.substr(4, 2), 16) / 255;

    let k = 1 - Math.max(r, g, b);
    let c = (1 - r - k) / (1 - k) || 0;
    let m = (1 - g - k) / (1 - k) || 0;
    let y = (1 - b - k) / (1 - k) || 0;

    return {
        c: Math.round(c * 100),
        m: Math.round(m * 100),
        y: Math.round(y * 100),
        k: Math.round(k * 100)
    };
}

function cmykToRgb(c, m, y, k) {
    c /= 100; m /= 100; y /= 100; k /= 100;
    const r = 255 * (1 - c) * (1 - k);
    const g = 255 * (1 - m) * (1 - k);
    const b = 255 * (1 - y) * (1 - k);
    return {
        r: Math.round(r),
        g: Math.round(g),
        b: Math.round(b)
    };
}

function rgbToHex(r, g, b) {
    return "#" + [r, g, b].map(x => {
        const hex = x.toString(16);
        return hex.length === 1 ? "0" + hex : hex;
    }).join("");
}

$('.color-input').on('click focus', function () {
    $('.cmyk-popup').hide(); // Hide other popups
    const index = $(this).data('index');
    $(`#cmyk-picker-${index}`).show();
});

$('.cmyk-popup .cmyk-input').on('input', function () {
    const popup = $(this).closest('.cmyk-popup');
    const index = popup.data('index');

    // Get all CMYK values
    const c = parseInt(popup.find('[data-channel="c"]').val()) || 0;
    const m = parseInt(popup.find('[data-channel="m"]').val()) || 0;
    const y = parseInt(popup.find('[data-channel="y"]').val()) || 0;
    const k = parseInt(popup.find('[data-channel="k"]').val()) || 0;

    const rgb = cmykToRgb(c, m, y, k);
    const hex = rgbToHex(rgb.r, rgb.g, rgb.b);
    const cmykString = `C:${c} M:${m} Y:${y} K:${k}`;

    // Set hex color to input
    $(`#color-input-${index}`).val(cmykString).css('background-color', hex).css('color', hex);

    // Set CMYK string
    $(`#cmykOutputinput_${index}`).val(hex);
    $(`#cmykOutput_${index}`).text(cmykString);
});

// Hide CMYK popup if clicked outside
$(document).on('click', function (e) {
    if (!$(e.target).closest('.cmyk-popup, .color-input').length) {
        $('.cmyk-popup').hide();
        
    }    
});

$(document).on('keydown', '.cmyk-input', function (e) {    
    if (e.key === 'Enter') {
        e.preventDefault();                
    }
});

$(document).on('keydown', '#job_details_form', function (e) {    
    if (e.key === 'Enter') {
        e.preventDefault();                
    }    
});



// document.addEventListener('DOMContentLoaded', () => {
//     document.querySelectorAll('.color-input').forEach((input, index) => {
//         const pickr = Pickr.create({
//             el: input,
//             theme: 'classic',
//             default: '#cccccc',
//             components: {
//                 preview: true,
//                 hue: true,
//                 interaction: {
//                     input: true,
//                     save: true
//                 }
//             }
//         });

//         pickr.on('save', (color) => {
//             const hex = color.toHEXA().toString();
//             const shades = generateShades(hex);

//             input.value = shades[2]; // Or use shades[0], [1], etc. for lighter/darker shade
//             pickr.hide();
//             const cmyk = hexToCmyk(shades[2]);
            
//             // Show hex code in the corresponding <p> element
//             const outputId = input.id.replace('color-input-', 'cmykOutput_');
//             const outputInput = input.id.replace('color-input-', 'cmykOutputinput_');
//             document.getElementById(outputId).textContent = `CMYK(${cmyk.c}%, ${cmyk.m}%, ${cmyk.y}%, ${cmyk.k}%)`;
//             document.getElementById(outputInput).value = `CMYK(${cmyk.c}%, ${cmyk.m}%, ${cmyk.y}%, ${cmyk.k}%)`;
//         });

//         // Prevent numbers from being typed
//         input.addEventListener('keydown', function(e) {
//             if (!isNaN(parseInt(e.key))) e.preventDefault();
//         });
//     });
// });
</script>

