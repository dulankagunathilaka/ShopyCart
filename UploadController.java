package com.shopycart.controller;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;

import java.io.File;
import java.io.IOException;

@RestController
public class UploadController {

    @Value("${upload.path}")
    private String uploadDir;

    @PostMapping("/upload")
    public ResponseEntity<String> handleFileUpload(
            @RequestParam("name") String name,
            @RequestParam("category") String category,
            @RequestParam("description") String description,
            @RequestParam("price") String price,
            @RequestParam("quantity") String quantity,
            @RequestParam("image") MultipartFile imageFile) throws IOException {

        String fileName = imageFile.getOriginalFilename();
        File file = new File(uploadDir + "/" + fileName);
        imageFile.transferTo(file);

        // Save product details to database (you can use a service here)
        return ResponseEntity.ok("Product added successfully with image: " + fileName);
    }
}
