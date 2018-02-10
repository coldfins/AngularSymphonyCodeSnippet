package com.journaldev.spring.dao;

import java.util.List;

import javax.persistence.EntityManager;
import javax.persistence.PersistenceContext;

import org.springframework.stereotype.Repository;
import org.springframework.transaction.annotation.Propagation;
import org.springframework.transaction.annotation.Transactional;

import com.journaldev.spring.model.Student;

@Repository
@Transactional(propagation = Propagation.REQUIRED)
public class StudentDAOImpl implements StudentDAO {

	@PersistenceContext
	private EntityManager entityManager;

	public EntityManager getEntityManager() {
		return entityManager;
	}

	public void setEntityManager(EntityManager entityManager) {
		this.entityManager = entityManager;
	}

	@Override
	public void addStudent(Student student) {
		entityManager.persist(student);
	}

	@Override
	public List<Student> checkStudents(String name) {
		List<Student> studentsList = entityManager.createQuery(
				"Select s From Student s where name='" + name + "'",
				Student.class).getResultList();
		return studentsList;
	}

	@Override
	public List<Student> listStudents() {
		List<Student> studentsList = entityManager.createQuery(
				"Select s From Student s", Student.class).getResultList();
		return studentsList;
	}

}