package com.journaldev.spring.service;

import java.util.List;

import com.journaldev.spring.model.Student;

public interface StudentService {

	public void addStudent(Student student);

	public List<Student> checkStudents(String name);

	public List<Student> listStudents();

}
