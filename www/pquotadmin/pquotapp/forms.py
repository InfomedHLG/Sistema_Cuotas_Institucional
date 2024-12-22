#!/usr/bin/env python
from django import forms
from .models import Quota

class AddCuota(forms.ModelForm):
    class Meta:
        model = Quota
        fields = ['client_ip', 'organization', 'quota']

    def save(self, commit=True):
        instance = super().save(commit=False)
        instance.used = 0  # Establecemos un valor por defecto
        if commit:
            instance.save()
        return instance